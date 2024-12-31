<?php

namespace Src\Services;


require 'vendor/autoload.php';

use Suin\RSSWriter\Channel;
use Suin\RSSWriter\Feed;
use Suin\RSSWriter\Item;
use Src\Repositories\ReportRepository;
use Src\Repositories\AnimalTypeRepository;
use Src\Repositories\TagRepository;
use Src\Repositories\UserRepository;
use Src\Models\ModelAnimalType;
use Src\Models\ModelTag;
use Src\Models\ModelReport;

/**
 * Class RssService
 *
 * This class provides methods for generating RSS feeds for reports.
 */
class RssService
{
    private $reportRepository;
    private $animalTypeRepository;
    private $tagRepository;
    private $userRepository;

    /**
     * RssService constructor.
     *
     * Initializes the ReportRepository, AnimalTypeRepository, and TagRepository objects.
     */
    public function __construct()
    {
        $this->reportRepository = new ReportRepository();
        $this->animalTypeRepository = new AnimalTypeRepository();
        $this->tagRepository = new TagRepository();
        $this->userRepository = new UserRepository();
    }

    /**
     * Returns the RSS feed.
     *
     * This method retrieves the content of the RSS feed and returns it along with a HTTP status code.
     *
     * @return string The HTTP response status and body content.
     */
    public function getRssFeed()
    {
//        ob_start(); // Start output buffering
//        include(__DIR__ . '/../Pages/rss.php'); // Include the file using a file path
//        $pageContent = ob_get_clean(); // Get the contents of the output buffer and clean (erase) the output buffer
//
//        return [
//            'status_code_header' => 'HTTP/1.1 200 OK',
//            'body' => $pageContent
//        ];
        $pageContent = $this->createRssFeed();
        return $pageContent;
    }

    /**
     * Returns the last N items of the RSS feed.
     *
     * This method retrieves the content of the last N items of the RSS feed and returns it along with a HTTP status code.
     *
     * @return array The HTTP response status and body content.
     */
    public function getRssFeedLasts()
    {
//        ob_start(); // Start output buffering
//        include(__DIR__ . '/../Pages/rssLasts.php'); // Include the file using a file path
//        $pageContent = ob_get_clean(); // Get the contents of the output buffer and clean (erase) the output buffer

        $pageContent = $this->createRssFeed(10);
        return [
            'status_code_header' => 'HTTP/1.1 200 OK',
            'body' => $pageContent
        ];
    }

    /**
     * Creates the RSS feed.
     *
     * This method generates the RSS feed with all reports or the last N reports.
     *
     * @param int|null $n The number of last reports to include in the feed. If null, all reports are included.
     * @return string The generated RSS feed.
     */
    private function createRssFeed($n = null)
    {
        $reports = null;

        if ($n == null)
            $reports = $this->reportRepository->getAllReportsFilterByStatusReverseByTimestamp('active');
        else
            $reports = $this->reportRepository->getReportsOrderByTimestampReverseN(10);

        return $this->renderRSS($reports);

    }

    /**
     * Renders the RSS feed.
     *
     * This method takes a list of reports and generates an RSS feed from them.
     *
     * @param array $reports The reports to include in the feed.
     * @return string The generated RSS feed.
     */
    private function renderRSS($reports)
    {

        $feed = new Feed();

        // Create a new channel
        $channel = new Channel();
        $channel
            ->title('Fepa RSS feed - all active reports')
            ->description("Web application for reporting animal in distress")
            ->url("http://localhost/tw-project/index.php")
            ->language("en-US")
            ->copyright("Copyright 2024, Fepa")
            ->pubDate(time())
            ->lastBuildDate(time())
            ->ttl(60)
            ->appendTo($feed);

        // Add the channel to the feed
//        $feed->addChannel($channel);
        $tags = $this->tagRepository->getTags();

        $tagsString = '';
        foreach ($tags as $tag) {
            $tagsString .= $tag->getText() . ', ';
        }

        foreach ($reports as $report) {
            $animalTypeId = $report->getAnimalTypeId();
            $animalType = $this->animalTypeRepository->getAnimalTypeById($animalTypeId);
            $type = $animalType->getType();


            $description = '>>>Description: ' . $report->getDescription() . " >>>Location: " . $report->getLocation() .
            " >>>Phone number: " . $report->getPhoneNumber() .
            " >>>Animal type: $type" .
            " >>>Tags: $tagsString";

            // Create new items
            $item = new Item();
            $item
                ->title($report->getAnimalName())
                ->description($description)
                ->url("http://localhost/tw-project/index.php/reports/" . $report->getId())
                ->author($report->getName())
                ->pubDate(strtotime($report->getTimestamp()))
                ->guid("http://localhost/tw-project/reports/" . $report->getId(), true)
                ->preferCdata(true)
                ->appendTo($channel);
//            $channel->addItem($item);
        }

        return $feed->render();
    }
}