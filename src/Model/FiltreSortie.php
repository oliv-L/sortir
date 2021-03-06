<?php

namespace App\Model;

use App\Entity\Campus;


class FiltreSortie
{
    // ? : valide la possibilit√© d'un null
    private ?Campus $campus;
    private ?string $search = null;
    private ?\DateTimeInterface $dateMin = null;
    private ?\DateTimeInterface $dateMax = null;
    private $organisateur = false;
    private $inscrit = false;
    private $nonInscrit = false;
    private $sortiePassee = false;

    /**
     * @return Campus|null
     */
    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    /**
     * @param Campus|null $campus
     * @return FiltreSortie
     */
    public function setCampus(?Campus $campus): FiltreSortie
    {
        $this->campus = $campus;
        return $this;
    }


    /**
     * @return string|null
     */
    public function getSearch(): ?string
    {
        return $this->search;
    }

    /**
     * @param string|null $search
     * @return FiltreSortie
     */
    public function setSearch(?string $search): FiltreSortie
    {
        $this->search = $search;
        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getDateMin(): ?\DateTimeInterface
    {
        return $this->dateMin;
    }

    /**
     * @param \DateTimeInterface|null $dateMin
     * @return FiltreSortie
     */
    public function setDateMin(?\DateTimeInterface $dateMin): FiltreSortie
    {
        $this->dateMin = $dateMin;
        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getDateMax(): ?\DateTimeInterface
    {
        return $this->dateMax;
    }

    /**
     * @param \DateTimeInterface|null $dateMax
     * @return FiltreSortie
     */
    public function setDateMax(?\DateTimeInterface $dateMax): FiltreSortie
    {
        $this->dateMax = $dateMax;
        return $this;
    }


    /**
     * @return bool|null
     */
    public function getOrganisateur(): ?bool
    {
        return $this->organisateur;
    }

    /**
     * @param bool|null $organisateur
     * @return FiltreSortie
     */
    public function setOrganisateur(?bool $organisateur): FiltreSortie
    {
        $this->organisateur = $organisateur;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getInscrit(): ?bool
    {
        return $this->inscrit;
    }

    /**
     * @param bool|null $inscrit
     * @return FiltreSortie
     */
    public function setInscrit(?bool $inscrit): FiltreSortie
    {
        $this->inscrit = $inscrit;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getNonInscrit(): ?bool
    {
        return $this->nonInscrit;
    }

    /**
     * @param bool|null $nonInscrit
     * @return FiltreSortie
     */
    public function setNonInscrit(?bool $nonInscrit): FiltreSortie
    {
        $this->nonInscrit = $nonInscrit;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getSortiePassee(): ?bool
    {
        return $this->sortiePassee;
    }

    /**
     * @param bool|null $sortiePassee
     * @return FiltreSortie
     */
    public function setSortiePassee(?bool $sortiePassee): FiltreSortie
    {
        $this->sortiePassee = $sortiePassee;
        return $this;
    }


}