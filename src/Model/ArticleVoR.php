<?php

namespace eLife\ApiSdk\Model;

use DateTimeImmutable;
use eLife\ApiSdk\Collection\Sequence;
use GuzzleHttp\Promise\PromiseInterface;

final class ArticleVoR extends ArticleVersion
{
    private $impactStatement;
    private $banner;
    private $thumbnail;
    private $keywords;
    private $digest;
    private $content;
    private $references;
    private $decisionLetter;
    private $decisionLetterDescription;
    private $authorResponse;

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
        Sequence $authors,
        string $impactStatement = null,
        PromiseInterface $banner,
        Image $thumbnail = null,
        Sequence $keywords,
        PromiseInterface $digest,
        Sequence $content,
        Sequence $references,
        PromiseInterface $decisionLetter,
        Sequence $decisionLetterDescription,
        PromiseInterface $authorResponse
    ) {
        parent::__construct($id, $version, $type, $doi, $authorLine, $titlePrefix, $title, $published, $statusDate,
            $volume, $elocationId, $pdf, $subjects, $researchOrganisms, $abstract, $issue, $copyright, $authors);

        $this->impactStatement = $impactStatement;
        $this->banner = $banner;
        $this->thumbnail = $thumbnail;
        $this->keywords = $keywords;
        $this->digest = $digest;
        $this->content = $content;
        $this->references = $references;
        $this->decisionLetter = $decisionLetter;
        $this->decisionLetterDescription = $decisionLetterDescription;
        $this->authorResponse = $authorResponse;
    }

    /**
     * @return string|null
     */
    public function getImpactStatement()
    {
        return $this->impactStatement;
    }

    /**
     * @return Image|null
     */
    public function getBanner()
    {
        return $this->banner->wait();
    }

    /**
     * @return Image|null
     */
    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    public function getKeywords() : Sequence
    {
        return $this->keywords;
    }

    /**
     * @return ArticleSection|null
     */
    public function getDigest()
    {
        return $this->digest->wait();
    }

    public function getContent() : Sequence
    {
        return $this->content;
    }

    /**
     * @return Sequence|Reference[]
     */
    public function getReferences() : Sequence
    {
        return $this->references;
    }

    /**
     * @return ArticleSection|null
     */
    public function getDecisionLetter()
    {
        return $this->decisionLetter->wait();
    }

    /**
     * @return Sequence|Block[]
     */
    public function getDecisionLetterDescription() : Sequence
    {
        return $this->decisionLetterDescription;
    }

    /**
     * @return ArticleSection|null
     */
    public function getAuthorResponse()
    {
        return $this->authorResponse->wait();
    }
}