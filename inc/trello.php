<?php
class TrelloTable
{
    private string $apiKey = TRELLO_APIKEY;
    private string $token = TRELLO_TOKEN;
    private string $boardId = TRELLO_BOARD;

    private array $boardData;
    private array $members;
    private array $labels;
    private array $lists;

    private array $membercache;

    public function fetchUrlData(string $url, ?array $data = null): array
    {
        $query = array(
            'key' => $this->apiKey,
            'token' => $this->token
        );

        if (!empty($data)) {
            $query = array_merge($query, $data);
        }

        $url .= '?' . http_build_query($query);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);

        return json_decode($output, true);
    }

    public function getBoardData(): array
    {
        if (!empty($this->boardData)) {
            return $this->boardData;
        }

        $url = "https://api.trello.com/1/boards/{$this->boardId}";
        $data = $this->fetchUrlData($url);
        $this->boardData = $data;
        return $data;
    }

    public function getMembers(): array
    {
        if (!empty($this->members)) {
            return $this->members;
        }
        $url = "https://api.trello.com/1/boards/{$this->boardId}/members";
        return $this->fetchUrlData($url);
        $this->members = $data;
        return $data;
    }

    public function getMember(string $id, bool $detailed = true): array
    {
        if (!$detailed) {
            if (empty($this->members)) {
                $this->members = $this->getMembers();
            }

            foreach ($this->members as $member) {
                if ($member['id'] == $id) {
                    return $member;
                }
            }
        } else {
            $url = "https://api.trello.com/1/members/{$id}";
            if (empty($this->membercache[$id])) {
                $this->membercache[$id] = $this->fetchUrlData($url);
            }
            return $this->membercache[$id];
        }
    }

    public function getLabels(): array
    {
        if (!empty($this->labels)) {
            return $this->labels;
        }
        $url = "https://api.trello.com/1/boards/{$this->boardId}/labels";
        return $this->fetchUrlData($url);

        $this->labels = $data;
        return $data;
    }

    public function getUserCards(string $uid, int $limit = 10, null|string $list = null): array
    {
        $url = "https://api.trello.com/1/boards/{$this->boardId}/cards";

        $cards = $this->fetchUrlData($url);

        $userCards = array_filter($cards, function ($card) use ($uid) {
            return in_array($uid, $card['idMembers']); // Csak azok maradnak, ahol a felhasználó szerepel
        });

        if ($list !== NULL) {
            $list = $this->getListId($list);

            $userCards = array_filter($userCards, function ($card) use ($list) {
                return $card['idList'] == $list; // Csak azok maradnak, amelyek a megadott listában vannak
            });
        }

        $userCards = array_slice($userCards, 0, $limit);

        // rendezés határidőre, üresek a végére
        usort($userCards, function ($a, $b) {
            if (empty($a['due'])) {
                return 1;
            }
            if (empty($b['due'])) {
                return -1;
            }
            return strtotime($a['due']) - strtotime($b['due']);
        });

        return $userCards;
    }

    public function getProjectCards(Project $project, int $limit = 10, null|array $list = null, bool $onlyown = false): array
    {
        global $user;

        $url = "https://api.trello.com/1/boards/{$this->boardId}/cards";

        $cards = $this->fetchUrlData($url);

        // csak a projekt kártyái
        $label = $project->getTrelloId();
        $projectCards = array_filter($cards, function ($card) use ($label) {
            return in_array($label, $card['idLabels']);
        });

        // sorrendbe rendezés: elöl azok, melyeknek felhasználója én vagyok
        $uid = $user->getTrelloId();
        usort($projectCards, function ($b, $a) use ($uid) {
            $aHasUser = in_array($uid, $a['idMembers']);
            $bHasUser = in_array($uid, $b['idMembers']);

            if ($aHasUser && !$bHasUser) {
                return -1;
            }
            if (!$aHasUser && $bHasUser) {
                return 1;
            }

            return 0;
        });

        if ($onlyown) {
            // csak azok maradnak, melyekhez senki nincs csatolva vagy a felhasználó szerepel
            $projectCards = array_filter($projectCards, function ($card) use ($uid) {
                return empty($card['idMembers']) || in_array($uid, $card['idMembers']);
            });
        }

        if ($list !== NULL) {
            $listIds = array_map(function ($list) {
                return $this->getListId($list);
            }, $list);

            $projectCards = array_filter($projectCards, function ($card) use ($listIds) {
                return in_array($card['idList'], $listIds); // Csak azok maradnak, amelyek a megadott listákban vannak
            });

            // rendezés listák sorrendje szerint
            usort($projectCards, function ($a, $b) use ($listIds) {
                $aList = array_search($a['idList'], $listIds);
                $bList = array_search($b['idList'], $listIds);

                return $aList - $bList;
            });
        }

        // Rendezés határidőre, üresek a végére
        usort($projectCards, function ($a, $b) {
            if (empty($a['due'])) {
                return 1;
            }
            if (empty($b['due'])) {
                return -1;
            }
            return strtotime($a['due']) - strtotime($b['due']);
        });

        $projectCards = array_slice($projectCards, 0, $limit);
        return $projectCards;
    }

    public function getLists(): array
    {
        if (!empty($this->lists)) {
            return $this->lists;
        }
        $url = "https://api.trello.com/1/boards/{$this->boardId}/lists";
        return $this->fetchUrlData($url);

        $this->lists = $data;
        return $data;
    }

    public function getList(string $id): array
    {
        if (empty($this->lists)) {
            $this->lists = $this->getLists();
        }

        foreach ($this->lists as $list) {
            if ($list['id'] == $id) {
                return $list;
            }
        }
    }

    public function getProject(string $labelId): Project|false
    {
        global $con;
        $projectId = 0;
        $stmt = $con->prepare("SELECT `project_id` FROM trello_projects WHERE trello_id = ?");
        $stmt->bind_param("s", $labelId);
        $stmt->execute();
        $stmt->bind_result($projectId);
        if (!$stmt->fetch()) {
            return false;
        }
        if ($stmt->num_rows > 1) {
            return false;
        }
        $stmt->fetch();
        $stmt->close();

        return new Project($projectId);
    }

    public function getListId(string $name): string
    {
        if (empty($this->lists)) {
            $this->lists = $this->getLists();
        }

        foreach ($this->lists as $list) {
            if ($list['name'] == $name) {
                return $list['id'];
            }
        }

        return '';
    }
}
