<?php

namespace App\Entity;

use App\Repository\DailyReportRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DailyReportRepository::class)]
class DailyReport
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $date = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $state = null;

    /**
     * @var Collection<int, Habit>
     */
    #[ORM\ManyToMany(targetEntity: Habit::class, inversedBy: 'dailyReports')]
    private Collection $habit;

    public function __construct()
    {
        $this->habit = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state): static
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return Collection<int, Habit>
     */
    public function getHabit(): Collection
    {
        return $this->habit;
    }

    public function addHabit(Habit $habit): static
    {
        if (!$this->habit->contains($habit)) {
            $this->habit->add($habit);
        }

        return $this;
    }

    public function removeHabit(Habit $habit): static
    {
        $this->habit->removeElement($habit);

        return $this;
    }
}
