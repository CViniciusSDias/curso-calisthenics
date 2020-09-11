<?php

namespace Alura\Calisthenics\Domain\Student;

use Alura\Calisthenics\Domain\Video\Video;
use Ds\Map;

class Student
{
    private string $email;
    private \DateTimeInterface $bd;
    private Map $watchedVideos;
    private string $fName;
    private string $lName;
    public string $street;
    public string $number;
    public string $province;
    public string $city;
    public string $state;
    public string $country;

    public function __construct()
    {
        $this->watchedVideos = new Map();
    }

    public function setFName(string $fName): void
    {
        $this->fName = $fName;
    }

    public function setLName(string $lName): void
    {
        $this->lName = $lName;
    }

    public function getFullName(): string
    {
        return "{$this->fName} {$this->lName}";
    }

    public function setEmail(string $email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL) !== false) {
            $this->email = $email;
        } else {
            throw new \InvalidArgumentException('Invalid e-mail address');
        }
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getBd(): \DateTimeInterface
    {
        return $this->bd;
    }

    public function watch(Video $video, \DateTimeInterface $date)
    {
        $this->watchedVideos->put($video, $date);
    }

    public function hasAccess(): bool
    {
        if ($this->watchedVideos->count() > 0) {
            $this->watchedVideos->sort(fn (\DateTimeInterface $dateA, \DateTimeInterface $dateB) => $dateA <=> $dateB);
            /** @var \DateTimeInterface $firstDate */
            $firstDate = $this->watchedVideos->first();
            $today = new \DateTimeImmutable();

            if ($firstDate->diff($today)->days >= 90) {
                return false;
            } else {
                return true;
            }
        } else {
            return true;
        }
    }
}
