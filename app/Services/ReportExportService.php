<?php

namespace App\Services;

use Illuminate\Http\Response;
use Spatie\LaravelPdf\Facades\Pdf;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportExportService
{
    /**
     * Stream a CSV download with UTF-8 BOM for Excel/Unicode compatibility.
     */
    public function toCsv(string $filename, array $headers, iterable $rows): StreamedResponse
    {
        return response()->streamDownload(function () use ($headers, $rows) {
            $handle = fopen('php://output', 'w');
            // Write UTF-8 BOM so non-ASCII characters & currencies display correctly in Excel
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, $headers);

            foreach ($rows as $row) {
                fputcsv($handle, (array) $row);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    /**
     * Stream an Excel (.xls) spreadsheet download using formatted HTML/XML structure.
     */
    public function toExcel(string $filename, string $view, array $data): Response
    {
        $content = view($view, $data)->render();

        return response($content, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Cache-Control' => 'max-age=0, no-cache, must-revalidate, proxy-revalidate',
        ]);
    }

    /**
     * Generate a PDF download using Spatie Laravel-PDF with the pure PHP DOMPDF driver.
     */
    public function toPdf(string $filename, string $view, array $data, bool $landscape = false)
    {
        $builder = Pdf::view($view, $data)->format('a4');

        if ($landscape) {
            $builder->landscape();
        }

        return $builder->name($filename);
    }
}
