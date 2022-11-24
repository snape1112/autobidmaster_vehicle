<?php

namespace App\Entity;

use App\Repository\VehicleRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VehicleRepository::class)
 */
class Vehicle
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="smallint")
     */
    private $year;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $make;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $model;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $body_style;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $color;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $vin;

    /**
     * @ORM\Column(type="integer")
     */
    private $odometer;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $engine_size;

    /**
     * @ORM\Column(type="integer")
     */
    private $current_bid;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $sale_date;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $sale_start_at;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $location_name;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getMake(): ?string
    {
        return $this->make;
    }

    public function setMake(string $make): self
    {
        $this->make = $make;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getBodyStyle(): ?string
    {
        return $this->body_style;
    }

    public function setBodyStyle(?string $body_style): self
    {
        $this->body_style = $body_style;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getVin(): ?string
    {
        return $this->vin;
    }

    public function setVin(?string $vin): self
    {
        $this->vin = $vin;

        return $this;
    }

    public function getOdometer(): ?int
    {
        return $this->odometer;
    }

    public function setOdometer(int $odometer): self
    {
        $this->odometer = $odometer;

        return $this;
    }

    public function getEngineSize(): ?string
    {
        return $this->engine_size;
    }

    public function setEngineSize(?string $engine_size): self
    {
        $this->engine_size = $engine_size;

        return $this;
    }

    public function getCurrentBid(): ?int
    {
        return $this->current_bid;
    }

    public function setCurrentBid(int $current_bid): self
    {
        $this->current_bid = $current_bid;

        return $this;
    }

    public function getSaleDate(): ?\DateTimeInterface
    {
        return $this->sale_date;
    }

    public function setSaleDate(?\DateTimeInterface $sale_date): self
    {
        $this->sale_date = $sale_date;

        return $this;
    }

    public function getSaleStartAt(): ?\DateTimeInterface
    {
        return $this->sale_start_at;
    }

    public function setSaleStartAt(?\DateTimeInterface $sale_start_at): self
    {
        $this->sale_start_at = $sale_start_at;

        return $this;
    }

    public function getLocationName(): ?string
    {
        return $this->location_name;
    }

    public function setLocationName(string $location_name): self
    {
        $this->location_name = $location_name;

        return $this;
    }
}
