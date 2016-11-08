<?php

namespace eLife\ApiSdk\Model;

use DateTimeImmutable;
use eLife\ApiSdk\Collection\Sequence;
use GuzzleHttp\Promise\PromiseInterface;

abstract class ArticleVersion implements Model
{
    private $id;
    private $version;
    private $type;
    private $doi;
    private $authorLine;
    private $titlePrefix;
    private $title;
    private $published;
    private $statusDate;
    private $volume;
    private $elocationId;
    private $pdf;
    private $subjects;
    private $researchOrganisms;
    private $abstract;
    private $issue;
    private $copyright;
    private $authors;

    /**
     * @internal
     */
    public function __construct(
        string $id,
        int $version,
        string $type,
        string $doi,
        string $authorLine,
        string $titlePrefix = null,
        string $title,
        DateTimeImmutable $published,
        DateTimeImmutable $statusDate,
        int $volume,
        string $elocationId,
        string $pdf = null,
        Sequence $subjects,
        array $researchOrganisms,
        PromiseInterface $abstract,
        PromiseInterface $issue,
        PromiseInterface $copyright,
        Sequence $authors
    ) {
        $this->id = $id;
        $this->version = $version;
        $this->type = $type;
        $this->doi = $doi;
        $this->authorLine = $authorLine;
        $this->titlePrefix = $titlePrefix;
        $this->title = $title;
        $this->published = $published;
        $this->statusDate = $statusDate;
        $this->volume = $volume;
        $this->elocationId = $elocationId;
        $this->pdf = $pdf;
        $this->subjects = $subjects;
        $this->researchOrganisms = $researchOrganisms;
        $this->abstract = $abstract;
        $this->issue = $issue;
        $this->copyright = $copyright;
        $this->authors = $authors;
    }

    final public function getId(): string
    {
        return $this->id;
    }

    final public function getVersion(): int
    {
        return $this->version;
    }

    final public function getType(): string
    {
        return $this->type;
    }

    final public function getDoi(): string
    {
        return $this->doi;
    }

    final public function getAuthorLine(): string
    {
        return $this->authorLine;
    }

    /**
     * @return string|null
     */
    final public function getTitlePrefix()
    {
        return $this->titlePrefix;
    }

    final public function getTitle(): string
    {
        return $this->title;
    }

    final public function getFullTitle() : string
    {
        return implode(': ', array_filter([$this->titlePrefix, $this->title]));
    }

    final public function getPublishedDate(): DateTimeImmutable
    {
        return $this->published;
    }

    final public function getStatusDate(): DateTimeImmutable
    {
        return $this->statusDate;
    }

    final public function getVolume(): int
    {
        return $this->volume;
    }

    final public function getElocationId(): string
    {
        return $this->elocationId;
    }

    /**
     * @return string|null
     */
    public function getPdf()
    {
        return $this->pdf;
    }

    /**
     * @return Sequence|Subject[]
     */
    final public function getSubjects() : Sequence
    {
        return $this->subjects;
    }

    /**
     * @return string[]
     */
    final public function getResearchOrganisms(): array
    {
        return $this->researchOrganisms;
    }

    /**
     * @return ArticleSection|null
     */
    final public function getAbstract()
    {
        return $this->abstract->wait();
    }

    /**
     * @return int|null
     */
    final public function getIssue()
    {
        return $this->issue->wait();
    }

    final public function getCopyright(): Copyright
    {
        return $this->copyright->wait();
    }

    final public function getAuthors(): Sequence
    {
        return $this->authors;
    }
}