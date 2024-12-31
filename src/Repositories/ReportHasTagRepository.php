<?php

namespace Src\Repositories;

use Exception;
use Src\Models\ModelReportHasTag;
use Src\Models\ModelTag;

/**
 * Class ReportHasTagRepository
 *
 * This class provides methods for interacting with the 'reports_has_tags' table in the database.
 */
class ReportHasTagRepository extends Repository
{
    /**
     * ReportHasTagRepository constructor.
     *
     * Initializes the ReportHasTagRepository object.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Creates a new report-has-tags record.
     *
     * This method takes a ModelReportHasTag object, extracts the report ID and tag ID, and attempts to create a new record in the 'reports_has_tags' table.
     * If the record is created successfully, it returns the ID of the new report-has-tags. If not, it returns -1.
     *
     * @param ModelReportHasTag $reportHasTags The report-has-tags to create.
     * @return int The ID of the new report-has-tags, or -1 if the creation failed.
     */
    public function createReportHasTags(ModelReportHasTag $reportHasTags) : int
    {
        try {
            $statement = $this->db->prepare('insert into reports_has_tags (Reports_id,Tags_id) VALUES(?,?)');
            $reportId = $reportHasTags->getReportId();
            $tagId = $reportHasTags->getTagId();
            $statement->bind_param('ii', $reportId, $tagId);
            $statement->execute();
        } catch (Exception $e) {
            echo 'Error in ' . __METHOD__ . ': ' . $e->getMessage();
        }

        return $statement->insert_id;
    }

    /**
     * Retrieves tags by report ID.
     *
     * This method takes a report ID, queries the 'reports_has_tags' table for matching records, and returns an array of ModelTag objects.
     *
     * @param int $reportId The ID of the report.
     * @return array The array of tag objects.
     */
    public function getTagsByReportId(int $reportId): array
    {
        $tags = [];
        try {
            $statement = $this->db->prepare('select * from tags join reports_has_tags on tags.id = reports_has_tags.Tags_id where Reports_id = ? ');
            $statement->bind_param('i', $reportId);
            $statement->execute();
        } catch (Exception $e) {
            echo 'Error in ' . __METHOD__ . ': ' . $e->getMessage();
        }

        foreach ($statement->get_result() as $row) {
            $tag = new ModelTag();
            $tag->setId($row['id']);
            $tag->setText($row['Text']);
            $tags[] = $tag;
        }

        return $tags;
    }

    /**
     * Deletes tags by report ID.
     *
     * This method takes a report ID and deletes all records in the 'reports_has_tags' table that match the ID.
     *
     * @param int $reportId The ID of the report.
     */
    public function deleteTagsByReportId(int $reportId) : void
    {
        try {
            $statement = $this->db->prepare('delete from reports_has_tags where Reports_id = ?');
            $statement->bind_param('i', $reportId);
            $statement->execute();
        } catch (Exception $e) {
            echo 'Error in ' . __METHOD__ . ': ' . $e->getMessage();
        }
    }

    /**
     * Retrieves reports with a specific tag.
     *
     * This method takes a tag ID, queries the 'reports_has_tags' table for matching records, and returns an array of ModelReportHasTag objects.
     *
     * @param int $tagId The ID of the tag.
     * @return array The array of report-has-tags objects.
     */
    public function getReportsWithTag(int $tagId) : array
    {
        $reports = [];
        try {
            $statement = $this->db->prepare('select * from reports join reports_has_tags on reports.id = reports_has_tags.Reports_id where Tags_id = ? ');
            $statement->bind_param('i', $tagId);
            $statement->execute();
        } catch (Exception $e) {
            echo 'Error in ' . __METHOD__ . ': ' . $e->getMessage();
        }

        foreach ($statement->get_result() as $row) {
            $report = new ModelReportHasTag();
            $report->setReportId($row['Reports_id']);
            $report->setTagId($row['Tags_id']);
            $reports[] = $report;
        }

        return $reports;
    }
}