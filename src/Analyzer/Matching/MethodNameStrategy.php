<?php
namespace Scheb\Tombstone\Analyzer\Matching;

use Scheb\Tombstone\Analyzer\TombstoneList;
use Scheb\Tombstone\Tombstone;
use Scheb\Tombstone\Vampire;

class MethodNameStrategy implements MatchingStrategyInterface
{
    /**
     * @param Vampire $vampire
     * @param TombstoneList $tombstoneList
     *
     * @return Tombstone|null
     */
    public function matchVampireToTombstone(Vampire $vampire, TombstoneList $tombstoneList)
    {
        if ($matchingTombstones = $tombstoneList->getInMethod($vampire->getMethod())) {
            if (count($matchingTombstones) === 1) {
                return $matchingTombstones[0];
            }
        }

        return null;
    }
}
