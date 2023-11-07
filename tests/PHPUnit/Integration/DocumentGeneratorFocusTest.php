<?php

/**
 * @file This file is part of the PdfParser library.
 *
 * @author  Konrad Abicht <k.abicht@gmail.com>
 *
 * @date    2020-06-01
 *
 * @license LGPLv3
 *
 * @url     <https://github.com/smalot/pdfparser>
 *
 *  PdfParser is a pdf library written in PHP, extraction oriented.
 *  Copyright (C) 2017 - Sébastien MALOT <sebastien@malot.fr>
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Lesser General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Lesser General Public License for more details.
 *
 *  You should have received a copy of the GNU Lesser General Public License
 *  along with this program.
 *  If not, see <http://www.pdfparser.org/sites/default/LICENSE.txt>.
 */

namespace PHPUnitTests\Integration;

use PHPUnitTests\TestCase;
use Smalot\PdfParser\Parser;

/**
 * Document related tests which focus on certain PDF generators.
 */
class DocumentGeneratorFocusTest extends TestCase
{
    /**
     * Test getText result.
     *
     * PDF generated with Chromium 116 via SaveAs-dialog.
     */
    public function testGetTextPull634Chromium(): void
    {
        $document = (new Parser())->parseFile($this->rootDir.'/samples/grouped-by-generator/R2RML-Spec_Generated_by_Chromium-SaveAs-PDF.pdf');

        self::assertStringContainsString('R2RML: RDB to RDF Mapping Language', $document->getText());
    }

    /**
     * Test getText result.
     *
     * PDF (v 1.4) generated with Inkscape 0.92.
     */
    public function testGetTextPull634InkscapePDF14(): void
    {
        $document = (new Parser())->parseFile($this->rootDir.'/samples/grouped-by-generator/SimpleImage_Generated_by_Inkscape-0.92_PDF-v1.4.pdf');

        self::assertEquals('TEST', $document->getText());
    }

    /**
     * Test getText result.
     *
     * PDF (v 1.5) generated with Inkscape 0.92.
     */
    public function testGetTextPull634InkscapePDF15(): void
    {
        $document = (new Parser())->parseFile($this->rootDir.'/samples/grouped-by-generator/SimpleImage_Generated_by_Inkscape-0.92_PDF-v1.5.pdf');

        self::assertEquals('TEST', $document->getText());
    }

    /**
     * Test getText result.
     *
     * PDF (1.4) generated with LibreOffice Writer (6.4).
     *
     * @see https://help.libreoffice.org/6.4/en-US/text/shared/01/ref_pdf_export.html
     */
    public function testGetTextPull634LibreOffice(): void
    {
        $document = (new Parser())->parseFile($this->rootDir.'/samples/grouped-by-generator/RichDocument_Generated_by_Libreoffice-6.4_PDF-v1.4.pdf');

        self::assertStringContainsString(
            'Some currency symbols: £, €, ¥'."\n".'German characters: ÄÖÜß',
            $document->getText()
        );
    }

    /**
     * Test getText result.
     *
     * PDF (v 1.7) generated with Microsoft Print-to-PDF via Firefox.
     */
    public function testGetTextPull634MicrosoftPDF17(): void
    {
        $document = (new Parser())->parseFile($this->rootDir.'/samples/grouped-by-generator/Wikipedia-PDF_Generated_by_Microsoft_Print-to-PDF.pdf');

        $outputText = $document->getText();

        self::assertStringContainsString(
            'Adobe PDF icon'."\n".'Filename'."\n".'extension',
            $outputText
        );

        self::assertStringContainsString(
            'are necessary to make, use, sell, and distribute PDF-compliant',
            $outputText
        );
    }

    /**
     * Test Document functions.
     *
     * PDF (v 1.5) generated by Microsoft Word 2016.
     */
    public function testGetTextPull634MicrosoftWord2016(): void
    {
        $path = $this->rootDir.'/samples/grouped-by-generator/Mostly_German_Text_Generated_By_Microsoft_Word_2016.pdf';
        $document = (new Parser())->parseFile($path);

        $outputText = $document->getText();

        self::assertStringContainsString('(einschließlich Marktpflegequote) von 4 Mrd € angestrebt.', $outputText);

        // check whitespaces and tab usage
        self::assertStringContainsString(
            //           ,--- here is a tab
            'Fälligkeit: 	19. Oktober 2028 '."\n".
            'Zinszahlung: 19. Oktober gzj., Zinslaufbeginn 15. Juni 2023',
            $outputText
        );
    }

    /**
     * Test getText result.
     *
     * PDF (v 1.5) generated with Power PDF Create.
     */
    public function testGetTextPull634PowerPDFCreate(): void
    {
        $document = (new Parser())->parseFile($this->rootDir.'/samples/grouped-by-generator/Mostly_German_Text_Generated_by_Power_PDF_Create.pdf');

        $outputText = $document->getText();

        // located on page 1
        self::assertStringContainsString(
            'Index-Verhältniszahl: 1,17812 (am Valutierungstag 7. September 2023)',
            $outputText
        );

        // located on page 2
        self::assertStringContainsString(
            'Einbeziehung in den '."\n".
            'Börsenhandel: Dienstag, 5. September 2023 '."\n".
            'Valutierungstag: Donnerstag, 7. September 2023',
            $outputText
        );
    }

    /**
     * Test getText result.
     *
     * PDF generated from .docx with SmallPDF (https://smallpdf.com)
     */
    public function testGetTextPull634SmallPDF(): void
    {
        $document = (new Parser())->parseFile($this->rootDir.'/samples/grouped-by-generator/Document_Generated_by_SmallPDF.pdf');

        $outputText = $document->getText();

        // Actual encoded spaces in the document are preserved
        self::assertStringContainsString(
            'SmallPDF                       SMALLPDF                             SmallPDF',
            $outputText
        );

        // Hebrew text
        self::assertStringContainsString(
            'Hebrew Keyboard - תדלקמ תירבעב - Type Hebrew Online',
            $outputText
        );

        // Russian text
        self::assertStringContainsString(
            'Russian Keyboard - русская клавиатура - Type Russian',
            $outputText
        );
    }

    /**
     * Test getText result.
     *
     * PDF (1.6) generated by Word için Acrobat PDFMaker 17.
     */
    public function testGetTextPull634WordIcinAcrobatPDFMaker17(): void
    {
        $path = $this->rootDir.'/samples/grouped-by-generator/Mostly_Turkish_Text_Generated_by_Word_için_Acrobat_PDFMaker_17.pdf';
        $document = (new Parser())->parseFile($path);

        $outputText = $document->getText();

        self::assertStringContainsString(
            'İhracat ve döviz kazandırıcı hizmetler reeskont kredisi günlük',
            $outputText
        );

        // Unnecessary tabs are not inserted due to font-size being 1,
        // but the text-matrix scale is 9 or 10
        self::assertStringContainsString(
            'dikkate alınmasına devam edilecektir.',
            $outputText
        );

        // This encoded segment contains an escaped backslash right before
        // an octal code: \\\000. Account for this in Font::decodeOctal()
        // See: https://github.com/smalot/pdfparser/pull/640
        self::assertStringContainsString('Sayı: 2023-34', $outputText);
    }
}
