<?php

/**
 * Script Generator Buku Panduan Penggunaan EduTube LMS (.docx)
 * Menghasilkan dokumen Microsoft Word OpenXML (.docx) resmi lengkap dengan format, styling, tabel, dan 4 gambar screenshot.
 */

$outputDocx = __DIR__ . '/Panduan_Penggunaan_EduTube_LMS.docx';
$publicDocxDir = __DIR__ . '/public/docs';
if (!is_dir($publicDocxDir)) {
    mkdir($publicDocxDir, 0755, true);
}
$publicDocx = $publicDocxDir . '/Panduan_Penggunaan_EduTube_LMS.docx';

// Sumber gambar
$imgPaths = [
    'img1' => __DIR__ . '/Assets LMS/dasbor_siswa_katalog_kursus/screen.png',
    'img2' => __DIR__ . '/Assets LMS/tampilan_pemutar_video_kursus/screen.png',
    'img3' => __DIR__ . '/Assets LMS/dasbor_admin_manajer_kursus_video/screen.png',
    'img4' => __DIR__ . '/Assets LMS/modal_admin_impor_tautan_youtube/screen.png',
];

foreach ($imgPaths as $key => $p) {
    if (!file_exists($p)) {
        die("Error: Gambar $p tidak ditemukan!\n");
    }
}

// 1. [Content_Types].xml
$contentTypes = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">
  <Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>
  <Default Extension="xml" ContentType="application/xml"/>
  <Default Extension="png" ContentType="image/png"/>
  <Override PartName="/word/document.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.document.main+xml"/>
  <Override PartName="/word/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.styles+xml"/>
</Types>';

// 2. _rels/.rels
$rootRels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="word/document.xml"/>
</Relationships>';

// 3. word/_rels/document.xml.rels
$docRels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>
  <Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/image" Target="media/image1.png"/>
  <Relationship Id="rId3" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/image" Target="media/image2.png"/>
  <Relationship Id="rId4" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/image" Target="media/image3.png"/>
  <Relationship Id="rId5" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/image" Target="media/image4.png"/>
</Relationships>';

// 4. word/styles.xml
$stylesXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<w:styles xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main">
  <w:docDefaults>
    <w:rPrDefault>
      <w:rPr>
        <w:rFonts w:ascii="Calibri" w:hAnsi="Calibri" w:cs="Calibri"/>
        <w:sz w:val="22"/>
        <w:color w:val="334155"/>
        <w:lang w:val="id-ID"/>
      </w:rPr>
    </w:rPrDefault>
    <w:pPrDefault>
      <w:pPr>
        <w:spacing w:line="276" w:lineRule="auto" w:after="160"/>
      </w:pPr>
    </w:pPrDefault>
  </w:docDefaults>

  <w:style w:type="paragraph" w:styleId="Title">
    <w:name w:val="Title"/>
    <w:pPr>
      <w:jc w:val="center"/>
      <w:spacing w:before="400" w:after="200"/>
    </w:pPr>
    <w:rPr>
      <w:rFonts w:ascii="Segoe UI Semibold" w:hAnsi="Segoe UI Semibold"/>
      <w:b/>
      <w:sz w:val="56"/>
      <w:color w:val="1E293B"/>
    </w:rPr>
  </w:style>

  <w:style w:type="paragraph" w:styleId="Subtitle">
    <w:name w:val="Subtitle"/>
    <w:pPr>
      <w:jc w:val="center"/>
      <w:spacing w:before="0" w:after="400"/>
    </w:pPr>
    <w:rPr>
      <w:rFonts w:ascii="Segoe UI" w:hAnsi="Segoe UI"/>
      <w:sz w:val="26"/>
      <w:color w:val="64748B"/>
    </w:rPr>
  </w:style>

  <w:style w:type="paragraph" w:styleId="Heading1">
    <w:name w:val="heading 1"/>
    <w:pPr>
      <w:spacing w:before="400" w:after="160"/>
      <w:keepNext/>
    </w:pPr>
    <w:rPr>
      <w:rFonts w:ascii="Segoe UI Semibold" w:hAnsi="Segoe UI Semibold"/>
      <w:b/>
      <w:sz w:val="34"/>
      <w:color w:val="1E3A8A"/>
    </w:rPr>
  </w:style>

  <w:style w:type="paragraph" w:styleId="Heading2">
    <w:name w:val="heading 2"/>
    <w:pPr>
      <w:spacing w:before="280" w:after="120"/>
      <w:keepNext/>
    </w:pPr>
    <w:rPr>
      <w:rFonts w:ascii="Segoe UI Semibold" w:hAnsi="Segoe UI Semibold"/>
      <w:b/>
      <w:sz w:val="28"/>
      <w:color w:val="4338CA"/>
    </w:rPr>
  </w:style>

  <w:style w:type="paragraph" w:styleId="Heading3">
    <w:name w:val="heading 3"/>
    <w:pPr>
      <w:spacing w:before="200" w:after="80"/>
      <w:keepNext/>
    </w:pPr>
    <w:rPr>
      <w:rFonts w:ascii="Segoe UI Semibold" w:hAnsi="Segoe UI Semibold"/>
      <w:b/>
      <w:sz w:val="24"/>
      <w:color w:val="0F172A"/>
    </w:rPr>
  </w:style>
</w:styles>';

// Helper XML builders
function escapeXml($str) {
    return htmlspecialchars($str, ENT_QUOTES | ENT_XML1, 'UTF-8');
}

function p($text, $bold = false, $italic = false, $color = '334155', $size = 22, $align = 'left', $before = 0, $after = 120) {
    $bXml = $bold ? '<w:b/>' : '';
    $iXml = $italic ? '<w:i/>' : '';
    $alignXml = $align !== 'left' ? "<w:jc w:val=\"$align\"/>" : '';
    return "<w:p>
      <w:pPr>
        $alignXml
        <w:spacing w:before=\"$before\" w:after=\"$after\" w:line=\"276\" w:lineRule=\"auto\"/>
      </w:pPr>
      <w:r>
        <w:rPr>
          $bXml
          $iXml
          <w:color w:val=\"$color\"/>
          <w:sz w:val=\"$size\"/>
        </w:rPr>
        <w:t xml:space=\"preserve\">" . escapeXml($text) . "</w:t>
      </w:r>
    </w:p>";
}

function h1($text) {
    return "<w:p>
      <w:pPr>
        <w:pStyle w:val=\"Heading1\"/>
        <w:spacing w:before=\"400\" w:after=\"160\"/>
        <w:pBdr>
          <w:bottom w:val=\"single\" w:sz=\"12\" w:space=\"4\" w:color=\"CBD5E1\"/>
        </w:pBdr>
        <w:keepNext/>
      </w:pPr>
      <w:r>
        <w:rPr>
          <w:rFonts w:ascii=\"Segoe UI Semibold\" w:hAnsi=\"Segoe UI Semibold\"/>
          <w:b/>
          <w:sz w:val=\"34\"/>
          <w:color w:val=\"1E3A8A\"/>
        </w:rPr>
        <w:t>" . escapeXml($text) . "</w:t>
      </w:r>
    </w:p>";
}

function h2($text) {
    return "<w:p>
      <w:pPr>
        <w:pStyle w:val=\"Heading2\"/>
        <w:spacing w:before=\"300\" w:after=\"120\"/>
        <w:keepNext/>
      </w:pPr>
      <w:r>
        <w:rPr>
          <w:rFonts w:ascii=\"Segoe UI Semibold\" w:hAnsi=\"Segoe UI Semibold\"/>
          <w:b/>
          <w:sz w:val=\"28\"/>
          <w:color w:val=\"4338CA\"/>
        </w:rPr>
        <w:t>" . escapeXml($text) . "</w:t>
      </w:r>
    </w:p>";
}

function h3($text) {
    return "<w:p>
      <w:pPr>
        <w:pStyle w:val=\"Heading3\"/>
        <w:spacing w:before=\"200\" w:after=\"80\"/>
        <w:keepNext/>
      </w:pPr>
      <w:r>
        <w:rPr>
          <w:rFonts w:ascii=\"Segoe UI Semibold\" w:hAnsi=\"Segoe UI Semibold\"/>
          <w:b/>
          <w:sz w:val=\"24\"/>
          <w:color w:val=\"0F172A\"/>
        </w:rPr>
        <w:t>" . escapeXml($text) . "</w:t>
      </w:r>
    </w:p>";
}

function bullet($text, $level = 0, $boldPrefix = '', $normalSuffix = '') {
    $indent = 360 + ($level * 360);
    $runs = '';
    if ($boldPrefix) {
        $runs .= "<w:r><w:rPr><w:b/><w:color w:val=\"0F172A\"/><w:sz w:val=\"22\"/></w:rPr><w:t xml:space=\"preserve\">" . escapeXml($boldPrefix) . " </w:t></w:r>";
    }
    if ($normalSuffix) {
        $runs .= "<w:r><w:rPr><w:color w:val=\"334155\"/><w:sz w:val=\"22\"/></w:rPr><w:t xml:space=\"preserve\">" . escapeXml($normalSuffix) . "</w:t></w:r>";
    }
    if (!$boldPrefix && !$normalSuffix) {
        $runs .= "<w:r><w:rPr><w:color w:val=\"334155\"/><w:sz w:val=\"22\"/></w:rPr><w:t xml:space=\"preserve\">" . escapeXml($text) . "</w:t></w:r>";
    }

    return "<w:p>
      <w:pPr>
        <w:ind w:left=\"$indent\" w:hanging=\"240\"/>
        <w:spacing w:before=\"40\" w:after=\"80\" w:line=\"276\" w:lineRule=\"auto\"/>
      </w:pPr>
      <w:r>
        <w:rPr>
          <w:rFonts w:ascii=\"Arial\" w:hAnsi=\"Arial\"/>
          <w:color w:val=\"4F46E5\"/>
          <w:sz w:val=\"20\"/>
        </w:rPr>
        <w:t xml:space=\"preserve\">•  </w:t>
      </w:r>
      $runs
    </w:p>";
}

function callout($title, $content, $type = 'info') {
    $borderColor = match($type) {
        'success' => '10B981',
        'warning' => 'F59E0B',
        'alert' => 'EF4444',
        default => '4F46E5',
    };
    $bgColor = match($type) {
        'success' => 'ECFDF5',
        'warning' => 'FFFBEB',
        'alert' => 'FEF2F2',
        default => 'EEF2FF',
    };

    $normalized = str_replace(['\n', "\r\n", "\r"], "\n", $content);
    $lines = explode("\n", $normalized);
    $contentParagraphs = '';
    foreach ($lines as $line) {
        $contentParagraphs .= "<w:p>
            <w:pPr><w:spacing w:before=\"0\" w:after=\"60\"/></w:pPr>
            <w:r>
              <w:rPr><w:color w:val=\"334155\"/><w:sz w:val=\"21\"/></w:rPr>
              <w:t xml:space=\"preserve\">" . escapeXml($line) . "</w:t>
            </w:r>
          </w:p>";
    }

    return "<w:tbl>
      <w:tblPr>
        <w:tblW w:w=\"9200\" w:type=\"dxa\"/>
        <w:tblBorders>
          <w:top w:val=\"none\"/>
          <w:left w:val=\"single\" w:sz=\"24\" w:space=\"0\" w:color=\"$borderColor\"/>
          <w:bottom w:val=\"none\"/>
          <w:right w:val=\"none\"/>
        </w:tblBorders>
        <w:tblCellMar>
          <w:top w:w=\"140\" w:type=\"dxa\"/>
          <w:left w:w=\"240\" w:type=\"dxa\"/>
          <w:bottom w:w=\"140\" w:type=\"dxa\"/>
          <w:right w:w=\"240\" w:type=\"dxa\"/>
        </w:tblCellMar>
      </w:tblPr>
      <w:tr>
        <w:tc>
          <w:tcPr>
            <w:shd w:val=\"clear\" w:color=\"auto\" w:fill=\"$bgColor\"/>
          </w:tcPr>
          <w:p>
            <w:pPr><w:spacing w:before=\"60\" w:after=\"60\"/></w:pPr>
            <w:r>
              <w:rPr><w:b/><w:color w:val=\"$borderColor\"/><w:sz w:val=\"22\"/></w:rPr>
              <w:t>" . escapeXml($title) . "</w:t>
            </w:r>
          </w:p>
          $contentParagraphs
        </w:tc>
      </w:tr>
    </w:tbl>
    <w:p><w:pPr><w:spacing w:before=\"0\" w:after=\"100\"/></w:pPr></w:p>";
}

function imageDrawing($relId, $cx, $cy, $caption = '', $imgId = 1) {
    $imgXml = "<w:p>
      <w:pPr>
        <w:jc w:val=\"center\"/>
        <w:spacing w:before=\"160\" w:after=\"80\"/>
        <w:keepNext/>
      </w:pPr>
      <w:r>
        <w:drawing>
          <wp:inline distT=\"0\" distB=\"0\" distL=\"0\" distR=\"0\">
            <wp:extent cx=\"$cx\" cy=\"$cy\"/>
            <wp:effectExtent l=\"0\" t=\"0\" r=\"0\" b=\"0\"/>
            <wp:docPr id=\"$imgId\" name=\"Picture $imgId\"/>
            <wp:cNvGraphicFramePr>
              <a:graphicFrameLocks noChangeAspect=\"1\"/>
            </wp:cNvGraphicFramePr>
            <a:graphic>
              <a:graphicData uri=\"http://schemas.openxmlformats.org/drawingml/2006/picture\">
                <pic:pic>
                  <pic:nvPicPr>
                    <pic:cNvPr id=\"$imgId\" name=\"Picture $imgId\"/>
                    <pic:cNvPicPr/>
                  </pic:nvPicPr>
                  <pic:blipFill>
                    <a:blip r:embed=\"$relId\"/>
                    <a:stretch>
                      <a:fillRect/>
                    </a:stretch>
                  </pic:blipFill>
                  <pic:spPr>
                    <a:xfrm>
                      <a:off x=\"0\" y=\"0\"/>
                      <a:ext cx=\"$cx\" cy=\"$cy\"/>
                    </a:xfrm>
                    <a:prstGeom prst=\"rect\">
                      <a:avLst/>
                    </a:prstGeom>
                  </pic:spPr>
                </pic:pic>
              </a:graphicData>
            </a:graphic>
          </wp:inline>
        </w:drawing>
      </w:r>
    </w:p>";

    if ($caption) {
        $imgXml .= "<w:p>
          <w:pPr>
            <w:jc w:val=\"center\"/>
            <w:spacing w:before=\"40\" w:after=\"200\"/>
          </w:pPr>
          <w:r>
            <w:rPr>
              <w:i/>
              <w:color w:val=\"64748B\"/>
              <w:sz w:val=\"19\"/>
            </w:rPr>
            <w:t>" . escapeXml($caption) . "</w:t>
          </w:r>
        </w:p>";
    }

    return $imgXml;
}

function pageBreak() {
    return '<w:p><w:r><w:br w:type="page"/></w:r></w:p>';
}

function tableRoles() {
    return '<w:tbl>
      <w:tblPr>
        <w:tblW w:w="9200" w:type="dxa"/>
        <w:tblBorders>
          <w:top w:val="single" w:sz="4" w:space="0" w:color="CBD5E1"/>
          <w:left w:val="single" w:sz="4" w:space="0" w:color="CBD5E1"/>
          <w:bottom w:val="single" w:sz="4" w:space="0" w:color="CBD5E1"/>
          <w:right w:val="single" w:sz="4" w:space="0" w:color="CBD5E1"/>
          <w:insideH w:val="single" w:sz="4" w:space="0" w:color="E2E8F0"/>
          <w:insideV w:val="single" w:sz="4" w:space="0" w:color="E2E8F0"/>
        </w:tblBorders>
        <w:tblCellMar>
          <w:top w:w="120" w:type="dxa"/>
          <w:left w:w="160" w:type="dxa"/>
          <w:bottom w:w="120" w:type="dxa"/>
          <w:right w:w="160" w:type="dxa"/>
        </w:tblCellMar>
      </w:tblPr>
      <w:tr>
        <w:tc><w:tcPr><w:tcW w:w="2200" w:type="dxa"/><w:shd w:val="clear" w:color="auto" w:fill="F1F5F9"/></w:tcPr><w:p><w:r><w:rPr><w:b/><w:color w:val="0F172A"/></w:rPr><w:t>Peran (Role)</w:t></w:r></w:p></w:tc>
        <w:tc><w:tcPr><w:tcW w:w="3000" w:type="dxa"/><w:shd w:val="clear" w:color="auto" w:fill="F1F5F9"/></w:tcPr><w:p><w:r><w:rPr><w:b/><w:color w:val="0F172A"/></w:rPr><w:t>Ruang Lingkup Akses</w:t></w:r></w:p></w:tc>
        <w:tc><w:tcPr><w:tcW w:w="4000" w:type="dxa"/><w:shd w:val="clear" w:color="auto" w:fill="F1F5F9"/></w:tcPr><w:p><w:r><w:rPr><w:b/><w:color w:val="0F172A"/></w:rPr><w:t>Fitur Utama</w:t></w:r></w:p></w:tc>
      </w:tr>
      <w:tr>
        <w:tc><w:tcPr><w:tcW w:w="2200" w:type="dxa"/></w:tcPr><w:p><w:r><w:rPr><w:b/><w:color w:val="1E3A8A"/></w:rPr><w:t>Super Admin</w:t></w:r></w:p></w:tc>
        <w:tc><w:tcPr><w:tcW w:w="3000" w:type="dxa"/></w:tcPr><w:p><w:r><w:t>Seluruh Sistem &amp; Multi-Sekolah</w:t></w:r></w:p></w:tc>
        <w:tc><w:tcPr><w:tcW w:w="4000" w:type="dxa"/></w:tcPr><w:p><w:r><w:t>Kelola sekolah mitra, admin sekolah, manajemen pengguna global, kategori master, pengaturan platform, tiket bantuan pusat.</w:t></w:r></w:p></w:tc>
      </w:tr>
      <w:tr>
        <w:tc><w:tcPr><w:tcW w:w="2200" w:type="dxa"/></w:tcPr><w:p><w:r><w:rPr><w:b/><w:color w:val="4338CA"/></w:rPr><w:t>Admin Sekolah / Guru</w:t></w:r></w:p></w:tc>
        <w:tc><w:tcPr><w:tcW w:w="3000" w:type="dxa"/></w:tcPr><w:p><w:r><w:t>Lembaga / Sekolah Masing-Masing</w:t></w:r></w:p></w:tc>
        <w:tc><w:tcPr><w:tcW w:w="4000" w:type="dxa"/></w:tcPr><w:p><w:r><w:t>Course Builder, impor video YouTube otomatis, manajemen kurikulum/modul, kuis checkpoint, pendaftaran siswa manual &amp; impor CSV.</w:t></w:r></w:p></w:tc>
      </w:tr>
      <w:tr>
        <w:tc><w:tcPr><w:tcW w:w="2200" w:type="dxa"/></w:tcPr><w:p><w:r><w:rPr><w:b/><w:color w:val="059669"/></w:rPr><w:t>Siswa (Student)</w:t></w:r></w:p></w:tc>
        <w:tc><w:tcPr><w:tcW w:w="3000" w:type="dxa"/></w:tcPr><w:p><w:r><w:t>Portal Belajar &amp; Kelas Pribadi</w:t></w:r></w:p></w:tc>
        <w:tc><w:tcPr><w:tcW w:w="4000" w:type="dxa"/></w:tcPr><w:p><w:r><w:t>Akses katalog kursus, pemutar video YouTube, catatan berwaktu, forum diskusi, ujian/kuis interaktif, cetak &amp; unduh sertifikat resmi ber-QR Code.</w:t></w:r></w:p></w:tc>
      </w:tr>
    </w:tbl>
    <w:p><w:pPr><w:spacing w:before="0" w:after="160"/></w:pPr></w:p>';
}

// ==================== SUSUNAN DOKUMEN XML ====================
$docBody = '';

// COVER PAGE
$docBody .= '<w:p><w:pPr><w:spacing w:before="1200" w:after="100"/><w:jc w:val="center"/></w:pPr><w:r><w:rPr><w:rFonts w:ascii="Segoe UI Semibold" w:hAnsi="Segoe UI Semibold"/><w:b/><w:sz w:val="24"/><w:color w:val="4F46E5"/></w:rPr><w:t>BUKU PANDUAN PENGGUNA RESMI (USER MANUAL)</w:t></w:r></w:p>';
$docBody .= '<w:p><w:pPr><w:spacing w:before="100" w:after="240"/><w:jc w:val="center"/></w:pPr><w:r><w:rPr><w:rFonts w:ascii="Segoe UI" w:hAnsi="Segoe UI"/><w:b/><w:sz w:val="60"/><w:color w:val="0F172A"/></w:rPr><w:t>EduTube LMS</w:t></w:r></w:p>';
$docBody .= '<w:p><w:pPr><w:spacing w:before="0" w:after="800"/><w:jc w:val="center"/></w:pPr><w:r><w:rPr><w:rFonts w:ascii="Calibri" w:hAnsi="Calibri"/><w:sz w:val="26"/><w:color w:val="64748B"/></w:rPr><w:t>Platform Pembelajaran Berbasis Video YouTube, Kurikulum Interaktif, Evaluasi Kuis Checkpoint, dan Sertifikasi Digital Resmi</w:t></w:r></w:p>';

$docBody .= '<w:p><w:pPr><w:spacing w:before="600" w:after="200"/><w:jc w:val="center"/></w:pPr><w:r><w:rPr><w:rFonts w:ascii="Segoe UI" w:hAnsi="Segoe UI"/><w:sz w:val="22"/><w:color w:val="475569"/></w:rPr><w:t>Edisi Rilis Produksi v2.0 • Tahun 2026</w:t></w:r></w:p>';
$docBody .= '<w:p><w:pPr><w:spacing w:before="0" w:after="400"/><w:jc w:val="center"/></w:pPr><w:r><w:rPr><w:rFonts w:ascii="Segoe UI" w:hAnsi="Segoe UI"/><w:b/><w:sz w:val="22"/><w:color w:val="1E293B"/></w:rPr><w:t>Dikembangkan oleh Tim Rekayasa Perangkat Lunak EduTube</w:t></w:r></w:p>';

$docBody .= pageBreak();

// DAFTAR ISI RINGKAS
$docBody .= h1('Daftar Isi Buku Panduan');
$docBody .= bullet('Bab 1: Pendahuluan & Gambaran Umum Sistem', 0, '1.', 'Visi platform, arsitektur, dan matriks hak akses pengguna');
$docBody .= bullet('Bab 2: Panduan Penggunaan untuk Siswa (Learner Journey)', 0, '2.', 'Akses katalog, pemutar video, catatan berwaktu, diskusi, kuis, dan sertifikat');
$docBody .= bullet('Bab 3: Panduan untuk Admin Sekolah & Instruktur (Teacher Journey)', 0, '3.', 'Manajer kursus, Course Builder, impor video YouTube, kurikulum, dan manajemen siswa');
$docBody .= bullet('Bab 4: Panduan untuk Super Administrator (Platform Management)', 0, '4.', 'Multi-sekolah, administrasi user, master kategori, konfigurasi platform, dan helpdesk');
$docBody .= bullet('Bab 5: Ketentuan Penerbitan Sertifikat Kelulusan', 0, '5.', 'Syarat 100% video selesai dan lulus seluruh kuis checkpoint evaluasi');
$docBody .= bullet('Bab 6: Tanya Jawab Umum (FAQ) & Solusi Kendala Teknis', 0, '6.', 'Penyelesaian masalah umum dan verifikasi kode sertifikat');

$docBody .= pageBreak();

// BAB 1: PENDAHULUAN
$docBody .= h1('Bab 1: Pendahuluan & Gambaran Umum Sistem');
$docBody .= p('EduTube LMS adalah Learning Management System (LMS) modern generasi terbaru yang dirancang untuk mengintegrasikan potensi tak terbatas konten edukasi YouTube ke dalam ekosistem pembelajaran yang terstruktur, terarah, bebas distraksi, dan terukur secara akademis.');
$docBody .= p('Melalui EduTube LMS, institusi pendidikan dan guru dapat menyusun kurikulum berbasis video YouTube tanpa iklan, melengkapi materi dengan kuis checkpoint interaktif, menyediakan catatan berwaktu (timestamped notes), serta secara otomatis menerbitkan sertifikat digital resmi ber-QR Code bagi siswa yang telah menuntaskan seluruh materi dan lulus ujian.');

$docBody .= h2('1.1 Keunggulan Utama EduTube LMS');
$docBody .= bullet('Streaming Cepat & Hemat Bandwidth:', 0, '•', 'Memanfaatkan infrastruktur CDN global YouTube sehingga pemutaran video berjalan mulus tanpa membebani server lokal sekolah.');
$docBody .= bullet('Bebas Distraksi & Iklan:', 0, '•', 'Pemutar video tertanam secara khusus mematikan rekomendasi acak YouTube, menjaga fokus belajar siswa tetap tinggi.');
$docBody .= bullet('Kurikulum Bertingkat (Modular):', 0, '•', 'Materi tersusun rapi dalam modul-modul tematik yang dapat dibuka secara progresif.');
$docBody .= bullet('Checkpoint Kuis Terintegrasi:', 0, '•', 'Setiap materi penting dapat disertai kuis evaluasi untuk memvalidasi pemahaman materi sebelum siswa dapat lulus.');
$docBody .= bullet('Sertifikasi Digital Ber-QR Code:', 0, '•', 'Sertifikat otomatis terbit jika siswa telah menonton 100% video dan lulus seluruh kuis, dilengkapi verifikasi publik anti-pemalsuan.');

$docBody .= h2('1.2 Matriks Hak Akses & Peran Pengguna');
$docBody .= p('EduTube LMS menerapkan Role-Based Access Control (RBAC) ketat dengan tiga level peran utama:');
$docBody .= tableRoles();

$docBody .= pageBreak();

// BAB 2: PANDUAN SISWA
$docBody .= h1('Bab 2: Panduan Penggunaan untuk Siswa');
$docBody .= p('Sebagai siswa, Anda dapat menjelajahi seluruh kursus yang disediakan oleh sekolah, memantau kemajuan belajar, mengambil catatan berwaktu, berdiskusi di forum materi, dan mengklaim sertifikat kelulusan.');

$docBody .= h2('2.1 Dasbor Siswa & Eksplorasi Katalog Kursus');
$docBody .= p('Saat pertama kali masuk (login) sebagai siswa, Anda akan diarahkan ke halaman Dasbor Utama dan Katalog Kursus. Halaman ini dirancang sangat intuitif dengan statistik kemajuan belajar real-time.');
$docBody .= bullet('Pencarian Kursus Instan:', 0, '1.', 'Gunakan kotak pencarian di bagian atas untuk menemukan kursus berdasarkan judul, topik, atau nama instruktur.');
$docBody .= bullet('Filter Status Belajar:', 0, '2.', 'Tersedia tab filter "Semua", "Sedang Berjalan", "Selesai", dan "Tersimpan (Bookmark)" untuk mengelompokkan kursus.');
$docBody .= bullet('Kartu Kursus Interaktif:', 0, '3.', 'Setiap kartu menampilkan gambar thumbnail, nama pengajar, jumlah pelajaran, durasi, progress bar, dan tombol aksi langsung.');

// GAMBAR 1
$docBody .= imageDrawing('rId2', 4000000, 5688888, 'Gambar 2.1: Tampilan Dasbor Siswa dan Katalog Kursus EduTube LMS', 1);

$docBody .= h2('2.2 Ruang Belajar & Pemutar Video Interaktif (Classroom)');
$docBody .= p('Klik tombol "Mulai Belajar" atau "Lanjutkan Belajar" pada kartu kursus untuk memasuki Ruang Belajar (Classroom). Layar belajar terbagi menjadi dua area utama:');
$docBody .= bullet('Area Kiri (Pemutar Video & Konten):', 0, 'a.', 'Menampilkan video YouTube dengan kontrol pemutaran, lompat waktu, pengaturan kecepatan (0.5x - 2x), tombol "Tandai Selesai", serta tombol materi "Sebelumnya" dan "Berikutnya".');
$docBody .= bullet('Area Kanan (Silabus & Kemajuan):', 0, 'b.', 'Menampilkan daftar modul dan materi pelajaran yang dapat diklik langsung, status centang hijau pada materi yang telah selesai, dan indikator kelulusan sertifikat.');

// GAMBAR 2
$docBody .= imageDrawing('rId3', 4000000, 5382674, 'Gambar 2.2: Tampilan Ruang Belajar (Classroom), Pemutar Video, dan Silabus', 2);

$docBody .= h2('2.3 Fitur Produktivitas Belajar Siswa');
$docBody .= bullet('Catatan Berwaktu (Timestamped Notes):', 0, '•', 'Siswa dapat mencatat poin penting saat video berjalan. Catatan otomatis menyimpan menit & detik video. Saat catatan diklik nanti, pemutar video akan langsung melompat ke detik tersebut.');
$docBody .= bullet('Forum Diskusi & Tanya Jawab:', 0, '•', 'Ajukan pertanyaan langsung di bawah video. Siswa lain dan instruktur dapat memberikan jawaban dan menyukai tanggapan yang bermanfaat.');
$docBody .= bullet('Berkas Sumber Daya (Resources):', 0, '•', 'Unduh materi pendukung seperti PDF, slide presentasi, tautan repositori GitHub, atau file latihan praktikum.');
$docBody .= bullet('Auto-Next Countdown:', 0, '•', 'Setelah video selesai ditonton hingga akhir, sistem secara otomatis menghitung mundur 5 detik dan memutar materi berikutnya.');

$docBody .= h2('2.4 Mengerjakan Kuis Checkpoint & Evaluasi');
$docBody .= p('Jika suatu materi dilengkapi kuis evaluasi, tombol kuis akan aktif di tab pelajaran:');
$docBody .= bullet('Jawab semua soal pilihan ganda yang disajikan.', 0, '1.');
$docBody .= bullet('Klik tombol "Kirim Jawaban Kuis".', 0, '2.');
$docBody .= bullet('Sistem langsung mengoreksi jawaban, menampilkan skor perolehan, batas nilai kelulusan (Passing Grade, default 75), dan penjelasan pembahasan soal.', 0, '3.');
$docBody .= bullet('Jika nilai belum mencapai batas lulus, siswa dapat meninjau materi kembali dan mengulang kuis hingga dinyatakan Lulus.', 0, '4.');

$docBody .= pageBreak();

// BAB 3: PANDUAN ADMIN & GURU
$docBody .= h1('Bab 3: Panduan Admin Sekolah & Instruktur');
$docBody .= p('Instruktur dan Admin Sekolah memiliki wewenang penuh untuk merancang kurikulum, mengunggah materi berbasis YouTube, mengelola soal ujian, serta mengadministrasi data siswa sekolah.');

$docBody .= h2('3.1 Dasbor Admin & Manajer Kursus');
$docBody .= p('Dasbor Admin menyajikan ringkasan metrik kesehatan sistem dan aktivitas belajar di sekolah Anda:');
$docBody .= bullet('Total Video Pelajaran:', 0, '•', 'Jumlah video materi aktif yang telah tersinkronisasi.');
$docBody .= bullet('Kursus Aktif:', 0, '•', 'Total kursus berstatus terpublikasi dan draf.');
$docBody .= bullet('Kesehatan YouTube API:', 0, '•', 'Status koneksi API dan validitas tautan video.');
$docBody .= bullet('Total Waktu Tonton:', 0, '•', 'Agregat jam belajar yang telah dihabiskan siswa.');

// GAMBAR 3
$docBody .= imageDrawing('rId4', 5400000, 4320000, 'Gambar 3.1: Dasbor Utama Admin dan Pengelola Video Pembelajaran', 3);

$docBody .= h2('3.2 Menambahkan Materi Video dengan Impor YouTube Cepat');
$docBody .= p('EduTube LMS menyediakan fitur impor cerdas yang memotong 90% waktu pembuatan materi. Anda hanya membutuhkan tautan (URL) video YouTube:');
$docBody .= bullet('Pilih Kursus Target dan Modul/Bab yang dituju.', 0, '1.');
$docBody .= bullet('Tempelkan URL video YouTube (mendukung format youtube.com, youtu.be, maupun YouTube Shorts).', 0, '2.');
$docBody .= bullet('Klik tombol "Ambil Otomatis": Sistem akan mengekstrak judul video, durasi presisi, thumbnail kualitas tinggi, dan status video secara otomatis.', 0, '3.');
$docBody .= bullet('Lengkapi deskripsi materi menggunakan teks kaya atau Markdown.', 0, '4.');
$docBody .= bullet('Klik "Publikasikan Pelajaran" untuk merilis materi ke siswa, atau "Simpan sebagai Draf" jika masih dalam peninjauan.', 0, '5.');

// GAMBAR 4
$docBody .= imageDrawing('rId5', 5400000, 4320000, 'Gambar 3.2: Modal Impor Video YouTube Cepat dan Otomatisasi Metadata', 4);

$docBody .= h2('3.3 Single-Page Course Builder & Pembuatan Kuis');
$docBody .= p('Melalui antarmuka Course Builder terpadu (/admin/courses/{id}/builder), instruktur dapat:');
$docBody .= bullet('Menyusun struktur modul pembelajaran dengan drag-and-drop urutan materi.', 0, '•');
$docBody .= bullet('Mengatur status publikasi kursus (Draf atau Publikasi).', 0, '•');
$docBody .= bullet('Membuat Kuis Evaluasi Kursus: Menentukan judul kuis, passing grade (misal: 80), serta opsi acak urutan soal (shuffle).', 0, '•');
$docBody .= bullet('Membuat Soal Kuis Materi: Memasukkan pertanyaan, opsi jawaban pilihan ganda (A, B, C, D), menentukan kunci jawaban benar, dan menuliskan teks pembahasan mendalam.', 0, '•');

$docBody .= h2('3.4 Manajemen Siswa: Impor CSV & Ekspor Data');
$docBody .= p('Admin Sekolah dapat mengelola akun siswa dengan mudah tanpa harus mendaftarkan satu per satu:');
$docBody .= bullet('Impor Siswa Massal via CSV:', 0, '•', 'Unduh template CSV yang disediakan di menu Siswa, isi data nama, email, dan kata sandi siswa, lalu unggah berkas CSV. Ratusan akun siswa siap digunakan dalam hitungan detik.');
$docBody .= bullet('Ekspor Data Siswa CSV:', 0, '•', 'Unduh seluruh riwayat siswa, sekolah, dan status akun ke dalam format lembar kerja Excel/CSV.');
$docBody .= bullet('Reset Password Siswa:', 0, '•', 'Jika siswa lupa kata sandi, admin dapat langsung mereset kata sandi melalui tombol aksi di tabel siswa.');

$docBody .= pageBreak();

// BAB 4: PANDUAN SUPER ADMIN
$docBody .= h1('Bab 4: Panduan Super Administrator');
$docBody .= p('Peran Super Administrator bertindak sebagai pengelola sistem global yang memfasilitasi operasional seluruh sekolah mitra pada instalasi EduTube LMS.');

$docBody .= h2('4.1 Manajemen Sekolah Mitra');
$docBody .= bullet('Mendaftarkan profil sekolah baru, alamat, logo, dan penanggung jawab sekolah.', 0, '•');
$docBody .= bullet('Mengatur status aktif/non-aktif institusi pendidikan mitra.', 0, '•');
$docBody .= bullet('Menghubungkan akun Admin Sekolah dengan data sekolah masing-masing.', 0, '•');

$docBody .= h2('4.2 Master Kategori Kursus');
$docBody .= p('Super Admin mengatur taksonomi kategori pembelajaran global (misalnya: Pemrograman Web, Desain UI/UX, Cloud & DevOps, Kecerdasan Buatan, dan Jaringan Komputer). Seluruh kursus yang dibuat oleh instruktur akan diklasifikasikan ke dalam kategori ini.');

$docBody .= h2('4.3 Pengaturan Platform Global & Tiket Bantuan');
$docBody .= bullet('Konfigurasi Global:', 0, '•', 'Mengatur nama instansi, logo platform, kontak dukungan, dan batas durasi sesi login.');
$docBody .= bullet('Manajemen Tiket Dukungan (Helpdesk):', 0, '•', 'Menerima dan merespons tiket pertanyaan atau kendala teknis yang dikirimkan oleh Admin Sekolah. Setiap tanggapan otomatis mengirimkan notifikasi surel resmi.');

$docBody .= pageBreak();

// BAB 5: KETENTUAN SERTIFIKAT KELULUSAN
$docBody .= h1('Bab 5: Ketentuan Penerbitan Sertifikat Kelulusan');
$docBody .= p('EduTube LMS menerapkan standar jaminan mutu pembelajaran yang ketat. Sertifikat kelulusan tidak diberikan secara cuma-cuma, melainkan sebagai bukti kompetensi nyata siswa.');

$docBody .= callout('ATURAN RESMI PEMBUKAAN SERTIFIKAT KELULUSAN', 'Sertifikat Kelulusan HANYA AKAN TERBUKA dan diterbitkan oleh sistem jika memenuhi DUA SYARAT MUTLAK secara bersamaan:\n1. Siswa telah menonton dan menyelesaikan 100% video materi dalam kursus tersebut.\n2. Siswa telah mengerjakan dan DINYATAKAN LULUS pada seluruh kuis evaluasi materi dan kuis kursus.', 'warning');

$docBody .= h2('5.1 Checklist Transparan pada Silabus Kelas');
$docBody .= p('Untuk menjamin transparansi, ruang belajar siswa menyajikan kartu status kelulusan di sidebar silabus:');
$docBody .= bullet('Saat Sertifikat Masih Terkunci:', 0, '•', 'Tampil status "TERKUNCI" dengan checklist interaktif: status tontonan video (misal: 4/7 Selesai) dan status kuis (misal: 0/1 Kuis Lulus). Siswa tahu persis syarat mana yang belum dipenuhi.');
$docBody .= bullet('Saat Seluruh Syarat Terpenuhi:', 0, '•', 'Kartu otomatis berubah menjadi hijau dengan lencana "LULUS" dan tombol hijau terang "Lihat & Cetak Sertifikat Kelulusan". Selain itu, modal perayaan kelulusan akan muncul seketika di layar.');

$docBody .= h2('5.2 Fitur Keamanan Sertifikat EduTube LMS');
$docBody .= bullet('Kode Registrasi Sertifikat Unik:', 0, '•', 'Setiap sertifikat memiliki nomor registrasi alfanumerik unik (contoh: CERT-92HP3CFPLY).');
$docBody .= bullet('QR Code Verifikasi Publik:', 0, '•', 'Sertifikat memuat kode QR yang dapat dipindai oleh siapapun (termasuk universitas atau calon perusahaan perekrut) untuk memverifikasi keaslian dokumen secara online.');
$docBody .= bullet('Cetak Langsung & Unduh Digital:', 0, '•', 'Siswa dapat mencetak sertifikat langsung dari browser atau mengunduh lembar sertifikat resmi beresolusi tinggi.');

$docBody .= pageBreak();

// BAB 6: FAQ & TROUBLESHOOTING
$docBody .= h1('Bab 6: Tanya Jawab Umum & Solusi Kendala Teknis');

$docBody .= h2('Q1: Mengapa video YouTube di kelas tidak dapat diputar?');
$docBody .= p('Jawab: Pastikan video YouTube yang diimpor tidak disetel ke mode "Private" (Pribadi). Video harus berstatus "Public" atau "Unlisted". Selain itu, pastikan pembuat video tidak menonaktifkan fitur "Allow Embedding" di YouTube Studio.');

$docBody .= h2('Q2: Progres video saya sudah 100%, mengapa sertifikat belum terbuka?');
$docBody .= p('Jawab: Periksa kartu "Syarat Sertifikat" di sidebar kanan. Kemungkinan besar kursus tersebut memiliki soal kuis evaluasi atau checkpoint yang belum Anda kerjakan atau belum mencapai passing grade kelulusan. Kerjakan kuis tersebut hingga berstatus Lulus agar sertifikat langsung terbit.');

$docBody .= h2('Q3: Apakah guru bisa menambahkan kuis checkpoint di tengah-tengah kursus yang sedang berjalan?');
$docBody .= p('Jawab: Ya. Guru dapat menambahkan atau memperbarui soal kuis kapan saja melalui menu Course Builder. Sistem akan secara otomatis menghitung ulang kelayakan sertifikat berdasarkan daftar kuis aktif terbaru.');

$docBody .= h2('Q4: Bagaimana cara memverifikasi keabsahan sertifikat siswa?');
$docBody .= p('Jawab: Cukup buka peramban dan kunjungi alamat URL yang tertera di sertifikat: http://domain-lms/sertifikat/{kode}, atau pindai QR Code yang tercetak di sudut bawah lembar sertifikat menggunakan kamera ponsel pintar Anda.');

$docBody .= '<w:p><w:pPr><w:spacing w:before="400" w:after="100"/><w:jc w:val="center"/></w:pPr><w:r><w:rPr><w:rFonts w:ascii="Calibri" w:hAnsi="Calibri"/><w:i/><w:color w:val="94A3B8"/><w:sz w:val="20"/></w:rPr><w:t>— Akhir Dokumen Panduan Penggunaan EduTube LMS —</w:t></w:r></w:p>';

// SECTPR (Page Margins: Standard 1 inch / 1440 twips)
$docBody .= '<w:sectPr>
  <w:pgSz w:w="12240" w:h="15840"/>
  <w:pgMar w:top="1440" w:right="1440" w:bottom="1440" w:left="1440" w:header="720" w:footer="720" w:gutter="0"/>
  <w:cols w:space="720"/>
  <w:docGrid w:linePitch="360"/>
</w:sectPr>';

// 5. word/document.xml
$documentXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<w:document xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main"
            xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"
            xmlns:m="http://schemas.openxmlformats.org/officeDocument/2006/math"
            xmlns:v="urn:schemas-microsoft-com:vml"
            xmlns:wp="http://schemas.openxmlformats.org/drawingml/2006/wordprocessingDrawing"
            xmlns:w10="urn:schemas-microsoft-com:office:word"
            xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main"
            xmlns:pic="http://schemas.openxmlformats.org/drawingml/2006/picture">
  <w:body>
    ' . $docBody . '
  </w:body>
</w:document>';

// ==================== BUNDLE ZIP KE FILE .DOCX ====================
if (file_exists($outputDocx)) {
    unlink($outputDocx);
}

$zip = new ZipArchive();
if ($zip->open($outputDocx, ZipArchive::CREATE) !== true) {
    die("Error: Gagal membuat arsip zip $outputDocx\n");
}

$zip->addFromString('[Content_Types].xml', $contentTypes);
$zip->addFromString('_rels/.rels', $rootRels);
$zip->addFromString('word/_rels/document.xml.rels', $docRels);
$zip->addFromString('word/styles.xml', $stylesXml);
$zip->addFromString('word/document.xml', $documentXml);

// Masukkan 4 gambar screenshot ke folder word/media/
$zip->addFile($imgPaths['img1'], 'word/media/image1.png');
$zip->addFile($imgPaths['img2'], 'word/media/image2.png');
$zip->addFile($imgPaths['img3'], 'word/media/image3.png');
$zip->addFile($imgPaths['img4'], 'word/media/image4.png');

$zip->close();

// Salin juga ke public/docs/ agar bisa diunduh via web browser jika diperlukan
copy($outputDocx, $publicDocx);

echo "Berhasil membuat berkas DOCX!\n";
echo "Lokasi Root: " . $outputDocx . " (" . round(filesize($outputDocx) / 1024, 2) . " KB)\n";
echo "Lokasi Publik: " . $publicDocx . "\n";
