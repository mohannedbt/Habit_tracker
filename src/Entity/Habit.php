<?php

namespace App\Entity;

use App\Repository\HabitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HabitRepository::class)]
class Habit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[ORM\Column(type: 'string', length: 7)]
    private ?string $color = null;
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $period = null;

    /**
     * @var Collection<int, DailyReport>
     */
    #[ORM\ManyToMany(targetEntity: DailyReport::class, mappedBy: 'habit')]
    private Collection $dailyReports;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $start_date = null;

    public function __construct()
    {
        $this->dailyReports = new ArrayCollection();
        $this->name = "";
        $this->description = "";
        $this->period = 0;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPeriod(): ?int
    {
        return $this->period;
    }

    public function setPeriod(int $period): static
    {
        $this->period = $period;

        return $this;
    }

    /**
     * @return Collection<int, DailyReport>
     */
    public function getDailyReports(): Collection
    {
        return $this->dailyReports;
    }

    public function addDailyReport(DailyReport $dailyReport): static
    {
        if (!$this->dailyReports->contains($dailyReport)) {
            $this->dailyReports->add($dailyReport);
            $dailyReport->addHabit($this);
        }

        return $this;
    }

    public function removeDailyReport(DailyReport $dailyReport): static
    {
        if ($this->dailyReports->removeElement($dailyReport)) {
            $dailyReport->removeHabit($this);
        }

        return $this;
    }

    public function getStartDate(): ?\DateTime
    {
        return $this->start_date;
    }

    // Allow null here by adding ?
    public function setStartDate(?\DateTime $start_date): static
    {
        $this->start_date = $start_date;

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
}
