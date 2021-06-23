<?php

namespace App\Service\Listener;


use App\Entity\Category\Category;
use App\Entity\Match\AbstractMatch;
use App\Service\Update\StandingsRowUpdate;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use SymfonyBundles\RedisBundle\Redis\ClientInterface;

class MatchScoreChangeListener implements EventSubscriber
{
    public const SCORE_CHANGE_QUEUE_KEY = "match.score.change";

    private ClientInterface $redis;
    private StandingsRowUpdate $rowUpdate;

    public function __construct(ClientInterface $redis, StandingsRowUpdate $rowUpdate)
    {
        $this->redis = $redis;
        $this->rowUpdate = $rowUpdate;
    }

    public function getSubscribedEvents(): array
    {
        return [Events::postUpdate];
    }

    /*
     * Called after match is updated with new score and entity Match is flushed to database.
     */
    public function postUpdate(LifecycleEventArgs $args): void
    {
        $match = $args->getEntity();

        if($match instanceof AbstractMatch){
            //put in queue first
            //get standings(home away total) with season AND type
            //get standingsRow(bunch of them) with competitor and standings
            //get all matches where there are any of competitors from $match, you need competition and season
                    //match must be unfinished

            $changeSet = $args->getEntityManager()->getUnitOfWork()->getEntityChangeSet($match);

            if(isset($changeSet["statusCode"]) && $changeSet["statusCode"][0] === 0){
                //match just finished and everything is already updated in MatchCommand
                return;
            }
            else{
                //match was already finished and score was changed subsequently
                echo "Change in match detected. Run 'php bin/console update:standings'\n";
                $this->redis->push(self::SCORE_CHANGE_QUEUE_KEY, $match->getId());
            }
        }
    }
}