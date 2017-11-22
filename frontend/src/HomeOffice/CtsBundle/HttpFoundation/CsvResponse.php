<?php

namespace HomeOffice\CtsBundle\HttpFoundation;

use Symfony\Component\HttpFoundation\Response;

/**
 * Class CsvResponse
 *
 * @package HomeOffice\CtsBundle\HttpFoundation
 */
class CsvResponse extends Response
{
    /**
     * @var []
     */
    protected $data;

    /**
     * @var string
     */
    protected $filename = 'export.csv';

    /**
     * Constructor
     *
     * @param array $data
     * @param int   $status
     * @param array $headers
     * @param bool  $includeTitles
     */
    public function __construct(array $data = [], $status = 200, $headers = [], $includeTitles = false)
    {
        parent::__construct('', $status, $headers);

        $this->setData($data, $includeTitles);
    }

    /**
     * Set Data
     *
     * @param array $data
     * @param bool  $includeTitles
     *
     * @return Response
     */
    public function setData(array $data, $includeTitles = false)
    {
        $output = fopen('php://temp', 'r+');

        if ($includeTitles && count($data)) {
            fputcsv($output, array_keys($data[0]));
        }

        foreach ($data as $row) {
            fputcsv($output, $row);
        }

        rewind($output);
        $this->data = '';
        while ($line = fgets($output)) {
            $this->data .= $line;
        }

        $this->data .= fgets($output);

        return $this->update();
    }

    /**
     * Get Filename
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set Filename
     *
     * @param $filename
     *
     * @return Response
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this->update();
    }

    /**
     * Update
     *
     * @return Response
     */
    protected function update()
    {
        $this->headers->set('Content-Disposition', sprintf('attachment; filename="%s"', $this->filename));

        if (!$this->headers->has('Content-Type')) {
            $this->headers->set('Content-Type', 'text/csv');
        }

        return $this->setContent($this->data);
    }
}
