<?php

namespace App\Interfaces;

/**
 * Interface Historisable
 */
interface Historisable{
    /**
     * retourne les attributs pertinents de l'occurence sous forme de tableau
     *
     * @return array
     */
    public function toArray();
}