<?php

/**
 * Script Generator Buku Panduan Penggunaan EduTube LMS (.docx)
 * Menghasilkan dokumen Microsoft Word OpenXML (.docx) resmi lengkap dengan format, styling, tabel, dan 4 gambar screenshot.
 */
$outputDocx = __DIR__.'/Panduan_Penggunaan_EduTube_LMS.docx';
$publicDocxDir = __DIR__.'/public/docs';
if (! is_dir($publicDocxDir)) {
    mkdir($publicDocxDir, 0755, true);
}
$publicDocx = $publicDocxDir.'/Panduan_Penggunaan_EduTube_LMS.docx';

// Sumber gambar
$imgPaths = [
    'img1' => __DIR__.'/Assets LMS/dasbor_siswa_katalog_kursus/screen.png',
    'img2' => __DIR__.'/Assets LMS/tampilan_pemutar_video_kursus/screen.png',
    'img3' => __DIR__.'/Assets LMS/dasbor_admin_manajer_kursus_video/screen.png',
    'img4' => __DIR__.'/Assets LMS/modal_admin_impor_tautan_youtube/screen.png',
];

foreach ($imgPaths as $key => $p) {
    if (! file_exists($p)) {
        exit("Error: Gambar $p tidak ditemukan!\n");
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
function escapeXml($str)
{
    return htmlspecialchars($str, ENT_QUOTES | ENT_XML1, 'UTF-8');
}

function p($text, $bold = false, $italic = false, $color = '334155', $size = 22, $align = 'left', $before = 0, $after = 120)
{
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
        <w:t xml:space=\"preserve\">".escapeXml($text).'</w:t>
      </w:r>
    </w:p>';
}

function h1($text)
{
    return '<w:p>
      <w:pPr>
        <w:pStyle w:val="Heading1"/>
        <w:spacing w:before="400" w:after="160"/>
        <w:pBdr>
          <w:bottom w:val="single" w:sz="12" w:space="4" w:color="CBD5E1"/>
        </w:pBdr>
        <w:keepNext/>
      </w:pPr>
      <w:r>
        <w:rPr>
          <w:rFonts w:ascii="Segoe UI Semibold" w:hAnsi="Segoe UI Semibold"/>
          <w:b/>
          <w:sz w:val="34"/>
          <w:color w:val="1E3A8A"/>
        </w:rPr>
        <w:t>'.escapeXml($text).'</w:t>
      </w:r>
    </w:p>';
}

function h2($text)
{
    return '<w:p>
      <w:pPr>
        <w:pStyle w:val="Heading2"/>
        <w:spacing w:before="300" w:after="120"/>
        <w:keepNext/>
      </w:pPr>
      <w:r>
        <w:rPr>
          <w:rFonts w:ascii="Segoe UI Semibold" w:hAnsi="Segoe UI Semibold"/>
          <w:b/>
          <w:sz w:val="28"/>
          <w:color w:val="4338CA"/>
        </w:rPr>
        <w:t>'.escapeXml($text).'</w:t>
      </w:r>
    </w:p>';
}

function h3($text)
{
    return '<w:p>
      <w:pPr>
        <w:pStyle w:val="Heading3"/>
        <w:spacing w:before="200" w:after="80"/>
        <w:keepNext/>
      </w:pPr>
      <w:r>
        <w:rPr>
          <w:rFonts w:ascii="Segoe UI Semibold" w:hAnsi="Segoe UI Semibold"/>
          <w:b/>
          <w:sz w:val="24"/>
          <w:color w:val="0F172A"/>
        </w:rPr>
        <w:t>'.escapeXml($text).'</w:t>
      </w:r>
    </w:p>';
}

function bullet($text, $level = 0, $boldPrefix = '', $normalSuffix = '')
{
    $indent = 360 + ($level * 360);
    $runs = '';
    if ($boldPrefix) {
        $runs .= '<w:r><w:rPr><w:b/><w:color w:val="0F172A"/><w:sz w:val="22"/></w:rPr><w:t xml:space="preserve">'.escapeXml($boldPrefix).' </w:t></w:r>';
    }
    if ($normalSuffix) {
        $runs .= '<w:r><w:rPr><w:color w:val="334155"/><w:sz w:val="22"/></w:rPr><w:t xml:space="preserve">'.escapeXml($normalSuffix).'</w:t></w:r>';
    }
    if (! $boldPrefix && ! $normalSuffix) {
        $runs .= '<w:r><w:rPr><w:color w:val="334155"/><w:sz w:val="22"/></w:rPr><w:t xml:space="preserve">'.escapeXml($text).'</w:t></w:r>';
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

function callout($title, $content, $type = 'info')
{
    $borderColor = match ($type) {
        'success' => '10B981',
        'warning' => 'F59E0B',
        'alert' => 'EF4444',
        default => '4F46E5',
    };
    $bgColor = match ($type) {
        'success' => 'ECFDF5',
        'warning' => 'FFFBEB',
        'alert' => 'FEF2F2',
        default => 'EEF2FF',
    };

    $normalized = str_replace(['\n', "\r\n", "\r"], "\n", $content);
    $lines = explode("\n", $normalized);
    $contentParagraphs = '';
    foreach ($lines as $line) {
        $contentParagraphs .= '<w:p>
            <w:pPr><w:spacing w:before="0" w:after="60"/></w:pPr>
            <w:r>
              <w:rPr><w:color w:val="334155"/><w:sz w:val="21"/></w:rPr>
              <w:t xml:space="preserve">'.escapeXml($line).'</w:t>
            </w:r>
          </w:p>';
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
              <w:t>".escapeXml($title)."</w:t>
            </w:r>
          </w:p>
          $contentParagraphs
        </w:tc>
      </w:tr>
    </w:tbl>
    <w:p><w:pPr><w:spacing w:before=\"0\" w:after=\"100\"/></w:pPr></w:p>";
}

function imageDrawing($relId, $cx, $cy, $caption = '', $imgId = 1)
{
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
        $imgXml .= '<w:p>
          <w:pPr>
            <w:jc w:val="center"/>
            <w:spacing w:before="40" w:after="200"/>
          </w:pPr>
          <w:r>
            <w:rPr>
              <w:i/>
              <w:color w:val="64748B"/>
              <w:sz w:val="19"/>
            </w:rPr>
            <w:t>'.escapeXml($caption).'</w:t>
          </w:r>
        </w:p>';
    }

    return $imgXml;
}

function pageBreak()
{
    return '<w:p><w:r><w:br w:type="page"/></w:r></w:p>';
}

function tableRoles()
{
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

// DAFTAR ISI RESMI LENGKAP
$docBody .= h1('Daftar Isi Buku Panduan');
$docBody .= bullet('Bab 1: Pendahuluan & Gambaran Umum Sistem', 0, 'Bab 1.', 'Fondasi platform, visi, alur kerja, dan matriks hak akses pengguna');
$docBody .= bullet('1.1 Visi & Latar Belakang EduTube LMS', 1, '1.1', 'Transformasi kurasi video YouTube menjadi platform belajar akademis');
$docBody .= bullet('1.2 Keunggulan Utama EduTube LMS', 1, '1.2', 'Bebas distraksi, hemat kuota server, kurikulum bertingkat, kuis checkpoint');
$docBody .= bullet('1.3 Alur Kerja Pembelajaran Terpadu (Learning Workflow)', 1, '1.3', 'Alur siswa, instruktur, dan otomatisasi kelulusan');
$docBody .= bullet('1.4 Matriks Hak Akses & Peran Pengguna (RBAC Matrix)', 1, '1.4', 'Tabel wewenang Super Admin, Admin Sekolah/Guru, dan Siswa');

$docBody .= bullet('Bab 2: Panduan Penggunaan untuk Siswa (Learner Journey)', 0, 'Bab 2.', 'Akses katalog, pemutar video, catatan berwaktu, diskusi, kuis, dan sertifikat');
$docBody .= bullet('2.1 Akses Masuk (Login) & Pengenalan Dasbor Siswa', 1, '2.1', 'Prosedur login, kartu statistik ringkasan, dan navigasi utama');
$docBody .= bullet('2.2 Eksplorasi Katalog Kursus & Fitur Pencarian Cerdas', 1, '2.2', 'Pencarian instan topik/instruktur, filter status, dan penanda bookmark');
$docBody .= bullet('2.3 Ruang Belajar & Pemutar Video Interaktif (Classroom)', 1, '2.3', 'Pemutar video YouTube nocookie, kontrol kecepatan, dan auto-next');
$docBody .= bullet('2.4 Fitur Produktivitas Belajar Siswa', 1, '2.4', 'Catatan berwaktu (timestamped notes), forum diskusi, unduh sumber daya');
$docBody .= bullet('2.5 Pengerjaan Kuis Checkpoint & Evaluasi Hasil Ujian', 1, '2.5', 'Pengerjaan soal pilihan ganda, submit jawaban, passing grade, dan pembahasan');
$docBody .= bullet('2.6 Klaim, Unduh, dan Cetak Sertifikat Digital', 1, '2.6', 'Akses halaman verifikasi, pencetakan lembar resmi, dan validasi QR Code');

$docBody .= bullet('Bab 3: Panduan untuk Admin Sekolah & Instruktur (Teacher Journey)', 0, 'Bab 3.', 'Manajer kursus, Course Builder, impor YouTube, kurikulum, dan manajemen siswa');
$docBody .= bullet('3.1 Dasbor Admin Sekolah & Ringkasan Metrik Pembelajaran', 1, '3.1', 'Total video aktif, jam tonton siswa, kesehatan API, dan analitik kelas');
$docBody .= bullet('3.2 Manajemen Kursus & Pembuatan Kursus Baru', 1, '3.2', 'Formulir pembuatan kursus, penetapan kategori, tingkat kesulitan, dan thumbnail');
$docBody .= bullet('3.3 Menambahkan Materi Video dengan Impor YouTube Cepat', 1, '3.3', 'Tempel tautan YouTube, ekstraksi instan judul & durasi, simpan draf/publikasi');
$docBody .= bullet('3.4 Penyusun Kurikulum (Single-Page Course Builder)', 1, '3.4', 'Pengorganisasian modul, pengurutan pelajaran, dan struktur silabus');
$docBody .= bullet('3.5 Pembuatan Kuis Evaluasi & Bank Soal Materi', 1, '3.5', 'Penyusunan pertanyaan, passing grade kelulusan, opsi acak, dan pembahasan');
$docBody .= bullet('3.6 Manajemen Berkas Sumber Daya Pembelajaran (Lesson Resources)', 1, '3.6', 'Penyematan materi PDF, slide presentasi, dan repositori praktikum');
$docBody .= bullet('3.7 Administrasi Siswa: Pendaftaran Manual, Impor CSV, & Ekspor Data', 1, '3.7', 'Pendaftaran mandiri, template CSV massal, reset kata sandi, dan unduh data');
$docBody .= bullet('3.8 Pusat Dukungan Sekolah & Pengajuan Tiket Bantuan (School Support)', 1, '3.8', 'Penyampaian kendala operasional ke Super Admin dan pelacakan status tiket');

$docBody .= bullet('Bab 4: Panduan untuk Super Administrator (Platform Management)', 0, 'Bab 4.', 'Multi-sekolah, administrasi user, master kategori, konfigurasi platform, dan helpdesk');
$docBody .= bullet('4.1 Dasbor Pengawasan Global & Metrik Ekosistem Multi-Sekolah', 1, '4.1', 'Pemantauan lintas instansi, total pengguna platform, dan antrean helpdesk');
$docBody .= bullet('4.2 Manajemen Master Sekolah Mitra (Multi-Tenancy)', 1, '4.2', 'Pendaftaran instansi baru, penetapan penanggung jawab, dan aktivasi sekolah');
$docBody .= bullet('4.3 Manajemen Pengguna Global & Penetapan Hak Akses (User Management)', 1, '4.3', 'Penetapan peran (Role Assignment), penonaktifan akun, dan reset akses');
$docBody .= bullet('4.4 Manajemen Master Kategori Kursus', 1, '4.4', 'Taksonomi keilmuan, penetapan slug, ikon kategori, dan warna visual');
$docBody .= bullet('4.5 Pengaturan Konfigurasi Platform Global', 1, '4.5', 'Identitas institusi, nama platform, kontak resmi, dan batas durasi sesi');
$docBody .= bullet('4.6 Helpdesk Pusat, Penanganan Tiket, & Diagnostik Sistem Server', 1, '4.6', 'Inbox tiket pusat, pengiriman tanggapan resmi, dan audit kesehatan server');

$docBody .= bullet('Bab 5: Ketentuan Penerbitan Sertifikat Kelulusan', 0, 'Bab 5.', 'Dua syarat mutlak kelulusan, transparansi checklist, dan keamanan dokumen');
$docBody .= bullet('5.1 Dua Syarat Mutlak Penerbitan Sertifikat', 1, '5.1', '100% video selesai ditonton dan seluruh kuis dinyatakan lulus');
$docBody .= bullet('5.2 Checklist Transparan pada Silabus Kelas', 1, '5.2', 'Kartu status kelulusan di sidebar kelas dan indikator kelengkapan materi');
$docBody .= bullet('5.3 Standar Keamanan & Verifikasi Sertifikat Resmi', 1, '5.3', 'Kode unik alfanumerik, verifikasi publik QR Code, dan tata letak dokumen');

$docBody .= bullet('Bab 6: Tanya Jawab Umum (FAQ) & Solusi Kendala Teknis', 0, 'Bab 6.', 'Penyelesaian masalah umum, kebijakan video, dan panduan kendala teknis');
$docBody .= bullet('6.1 Solusi Video YouTube Tidak Dapat Diputar (Private/Embedding)', 1, '6.1', 'Penyebab restriksi YouTube dan cara mengaktifkan perizinan semat');
$docBody .= bullet('6.2 Solusi Progres 100% Video tetapi Sertifikat Belum Terbit', 1, '6.2', 'Pemeriksaan status kelulusan kuis materi atau checkpoint evaluasi');
$docBody .= bullet('6.3 Penambahan Kuis di Tengah Kursus yang Sedang Berjalan', 1, '6.3', 'Perhitungan ulang otomatis kelayakan sertifikat bagi peserta kursus');
$docBody .= bullet('6.4 Verifikasi Keaslian Sertifikat Digital oleh Pihak Ketiga', 1, '6.4', 'Langkah pemindaian QR Code dan akses tautan publik tanpa login');
$docBody .= bullet('6.5 Panduan Reset Kata Sandi Akun Siswa dan Guru', 1, '6.5', 'Langkah penanganan akun lupa password oleh administrator');

$docBody .= pageBreak();

// ==================== BAB 1: PENDAHULUAN ====================
$docBody .= h1('Bab 1: Pendahuluan & Gambaran Umum Sistem');
$docBody .= p('EduTube LMS adalah Learning Management System (LMS) modern generasi terbaru yang dirancang untuk mengintegrasikan potensi tak terbatas konten edukasi YouTube ke dalam ekosistem pembelajaran yang terstruktur, terarah, bebas distraksi, dan terukur secara akademis.');
$docBody .= p('Platform ini mentransformasikan video pembelajaran daring menjadi kurikulum akademik bertingkat yang dilengkapi pos pemeriksaan pemahaman siswa (Checkpoint Quiz), catatan pembelajaran berwaktu (timestamped notes), forum interaksi dua arah, serta penerbitan sertifikat kompetensi digital resmi ber-QR Code.');

$docBody .= h2('1.1 Visi & Latar Belakang EduTube LMS');
$docBody .= p('YouTube merupakan repositori materi pembelajaran terbesar di dunia. Namun, pembelajaran mandiri langsung di YouTube seringkali gagal mencapai tujuan akademis akibat beberapa hambatan:');
$docBody .= bullet('Distraksi Rekomendasi Algoritma:', 0, '1.', 'Siswa rentan teralihkan oleh video hiburan atau konten non-edukasi yang terus disodorkan oleh algoritma beranda.');
$docBody .= bullet('Iklan yang Mengganggu Konsentrasi:', 0, '2.', 'Iklan komersial di tengah-tengah video memutus alur pemikiran dan menurunkan efektivitas belajar.');
$docBody .= bullet('Ketiadaan Evaluasi & Akuntabilitas:', 0, '3.', 'Menonton video saja tidak menjamin siswa memahami materi tanpa adanya kuis evaluasi terukur.');
$docBody .= p('EduTube LMS hadir sebagai solusi menyeluruh dengan mengurasi materi YouTube berkualitas ke dalam lingkungan belajar sekolah yang kondusif, terisolasi dari gangguan eksternal, dan dilengkapi instrumen pengukuran kompetensi siswa.');

$docBody .= h2('1.2 Keunggulan Utama EduTube LMS');
$docBody .= bullet('Streaming Cepat & Hemat Bandwidth Server:', 0, '•', 'Pemutaran video memanfaatkan infrastruktur CDN global YouTube yang andal sehingga sekolah tidak perlu berinvestasi pada server penyimpanan video berbiaya tinggi.');
$docBody .= bullet('Pemutar Bebas Distraksi & Iklan:', 0, '•', 'Implementasi pemutar video resmi YouTube Player API dengan parameter khusus yang mematikan rekomendasi video pihak ketiga.');
$docBody .= bullet('Kurikulum Bertingkat (Modular Hierarchy):', 0, '•', 'Materi pelajaran tersusun rapi dalam struktur hierarki: Kursus → Modul Pembelajaran → Pelajaran Video → Kuis Evaluasi.');
$docBody .= bullet('Pos Pemeriksaan Pemahaman (Checkpoint Quiz):', 0, '•', 'Kuis pilihan ganda terintegrasi langsung pada setiap topik penting dengan sistem koreksi instan dan pembahasan.');
$docBody .= bullet('Sertifikasi Kompetensi Digital Otomatis:', 0, '•', 'Sistem menerbitkan sertifikat digital ber-QR Code secara instan ketika siswa berhasil menuntaskan seluruh video dan lulus kuis.');

$docBody .= h2('1.3 Alur Kerja Pembelajaran Terpadu (Learning Workflow)');
$docBody .= p('Alur kerja operasional dalam platform EduTube LMS berjalan melalui siklus terintegrasi berikut:');
$docBody .= bullet('Fase 1: Konfigurasi Institusi & Kategori (Super Admin)', 0, 'a.', 'Super Admin mendaftarkan sekolah mitra, menunjuk Administrator Sekolah, dan menetapkan master kategori keilmuan.');
$docBody .= bullet('Fase 2: Penyusunan Kurikulum & Impor Video (Guru / Admin Sekolah)', 0, 'b.', 'Guru membuat kursus baru, menyusun modul tematik, mengimpor tautan YouTube, melampirkan berkas penunjang, dan menyusun kuis checkpoint.');
$docBody .= bullet('Fase 3: Pembelajaran Aktif & Interaksi Siswa (Siswa)', 0, 'c.', 'Siswa mengakses ruang belajar, memutar video tanpa jeda iklan, membuat catatan berwaktu, berdiskusi di forum, dan mengunduh berkas materi.');
$docBody .= bullet('Fase 4: Evaluasi & Penerbitan Sertifikat (Sistem Otomatis)', 0, 'd.', 'Siswa menyelesaikan kuis evaluasi. Sistem menghitung kelengkapan belajar secara real-time dan menerbitkan sertifikat resmi saat syarat terpenuhi 100%.');

$docBody .= h2('1.4 Matriks Hak Akses & Peran Pengguna (RBAC Matrix)');
$docBody .= p('EduTube LMS menerapkan Role-Based Access Control (RBAC) yang memisahkan wewenang secara tegas demi menjaga integritas data dan kelancaran operasional:');
$docBody .= tableRoles();

$docBody .= pageBreak();

// ==================== BAB 2: PANDUAN SISWA ====================
$docBody .= h1('Bab 2: Panduan Penggunaan untuk Siswa (Learner Journey)');
$docBody .= p('Sebagai siswa, Anda dapat menjelajahi seluruh kursus yang disediakan oleh sekolah, memantau kemajuan belajar secara real-time, mengambil catatan berwaktu, berinteraksi di forum diskusi materi, dan mengklaim sertifikat kelulusan.');

$docBody .= h2('2.1 Akses Masuk (Login) & Pengenalan Dasbor Siswa');
$docBody .= p('Untuk memulai proses belajar di EduTube LMS, ikuti langkah-langkah berikut:');
$docBody .= bullet('Buka Peramban:', 0, '1.', 'Buka Google Chrome, Mozilla Firefox, Microsoft Edge, atau Safari di komputer, tablet, maupun ponsel pintar Anda.');
$docBody .= bullet('Kunjungi URL Aplikasi:', 0, '2.', 'Akses tautan resmi LMS sekolah Anda (contoh: https://demo.gustiswandana.space atau portal sekolah terkait).');
$docBody .= bullet('Masukkan Kredensial:', 0, '3.', 'Ketik alamat surel (email) dan kata sandi yang telah diberikan oleh pihak sekolah, lalu klik tombol "Masuk ke Akun".');
$docBody .= bullet('Tinjau Ringkasan Belajar:', 0, '4.', 'Di bagian atas dasbor, Anda akan melihat kartu statistik yang menampilkan: Total Kursus Diikuti, Jumlah Kursus Selesai, Total Menit Waktu Belajar, dan Sertifikat yang Telah Diraih.');

$docBody .= h2('2.2 Eksplorasi Katalog Kursus & Fitur Pencarian Cerdas');
$docBody .= p('Halaman Katalog Kursus menyajikan seluruh materi pembelajaran yang terbuka untuk sekolah Anda:');
$docBody .= bullet('Kotak Pencarian Instan:', 0, '•', 'Ketik topik, judul teknologi, atau nama instruktur pada bilah pencarian untuk menyaring daftar kursus seketika.');
$docBody .= bullet('Tab Filter Status:', 0, '•', 'Gunakan tab "Semua", "Sedang Berjalan", "Selesai", dan "Disimpan (Bookmark)" untuk mengelompokkan tampilan kursus sesuai kebutuhan Anda.');
$docBody .= bullet('Kartu Kursus Interaktif:', 0, '•', 'Setiap kartu menampilkan gambar pratinjau (thumbnail), nama pengajar, tingkat kesulitan (Pemula, Menengah, Mahir), jumlah materi pelajaran, indikator progress bar belajar, dan tombol aksi "Mulai Belajar" atau "Lanjutkan Belajar".');

// GAMBAR 1
$docBody .= imageDrawing('rId2', 4000000, 5688888, 'Gambar 2.1: Tampilan Dasbor Siswa dan Katalog Kursus EduTube LMS', 1);

$docBody .= h2('2.3 Ruang Belajar & Pemutar Video Interaktif (Classroom)');
$docBody .= p('Klik kartu kursus untuk memasuki Ruang Belajar (Classroom). Antarmuka ruang belajar dirancang modern dengan tata letak dua kolom:');
$docBody .= bullet('Kolom Kiri (Pemutar Video & Kontrol):', 0, 'a.', 'Menampilkan video YouTube beresolusi tinggi dengan kendali pemutaran penuh: putar/jeda, pengaturan volume, opsi layar penuh (full screen), dan pengatur kecepatan pemutaran (0.5x, 1x, 1.25x, 1.5x, hingga 2x).');
$docBody .= bullet('Tombol Navigasi Materi:', 0, 'b.', 'Di bawah pemutar video terdapat tombol "Materi Sebelumnya", tombol "Tandai Selesai", dan tombol "Materi Berikutnya".');
$docBody .= bullet('Hitung Mundur Otomatis (Auto-Next Countdown):', 0, 'c.', 'Saat video selesai diputar hingga detik akhir, sistem secara otomatis menandai materi telah selesai dan menampilkan hitung mundur 5 detik sebelum memutar materi selanjutnya secara mulus.');
$docBody .= bullet('Kolom Kanan (Silabus & Kemajuan):', 0, 'd.', 'Menampilkan daftar modul dan silabus lengkap. Materi yang telah ditonton akan ditandai dengan ikon centang hijau. Di bagian atas silabus terdapat kartu indikator persyaratan sertifikat kelulusan.');

// GAMBAR 2
$docBody .= imageDrawing('rId3', 4000000, 5382674, 'Gambar 2.2: Tampilan Ruang Belajar (Classroom), Pemutar Video, dan Silabus', 2);

$docBody .= h2('2.4 Fitur Produktivitas Belajar Siswa');
$docBody .= p('Tepat di bawah video pelajaran, tersedia tab penunjang belajar untuk memaksimalkan retensi materi:');
$docBody .= bullet('Catatan Berwaktu (Timestamped Notes):', 0, '•', 'Tulis ringkasan atau konsep penting saat video sedang berlangsung. Sistem secara otomatis mencatat posisi menit dan detik video saat tombol simpan ditekan. Kapanpun Anda mengklik tanda waktu tersebut di kemudian hari, pemutar video akan langsung melompat ke detik yang dimaksud.');
$docBody .= bullet('Forum Diskusi & Tanya Jawab:', 0, '•', 'Gunakan tab diskusi untuk mengajukan pertanyaan bila menemui kendala pada materi tersebut. Guru pengampu dan teman sekelas dapat menjawab pertanyaan Anda secara terbuka.');
$docBody .= bullet('Sumber Daya Materi (Lesson Resources):', 0, '•', 'Unduh berkas pendukung yang telah diunggah guru, seperti dokumen PDF modul, slide presentasi materi, atau tautan repositori kode praktikum.');

$docBody .= h2('2.5 Pengerjaan Kuis Checkpoint & Evaluasi Hasil Ujian');
$docBody .= p('Untuk menguji pemahaman konsep, modul pembelajaran dapat dilengkapi kuis checkpoint pilihan ganda:');
$docBody .= bullet('Membuka Kuis:', 0, '1.', 'Pilih tab "Kuis Evaluasi" di ruang belajar atau klik tombol materi kuis di daftar silabus.');
$docBody .= bullet('Menjawab Pertanyaan:', 0, '2.', 'Baca setiap pertanyaan dengan teliti dan pilih opsi jawaban yang Anda yakini benar (opsi A, B, C, atau D).');
$docBody .= bullet('Mengirimkan Lembar Ujian:', 0, '3.', 'Setelah seluruh soal terjawab, klik tombol "Kirim Jawaban Kuis".');
$docBody .= bullet('Koreksi Otomatis & Pembahasan:', 0, '4.', 'Sistem seketika menghitung nilai Anda, menampilkan status kelulusan (Lulus atau Belum Lulus) berdasarkan batas nilai minimal (passing grade), serta menyajikan pembahasan terperinci pada setiap butir soal.');
$docBody .= bullet('Mekanisme Remedial:', 0, '5.', 'Apabila skor perolehan Anda belum mencapai batas kelulusan, Anda dipersilakan meninjau kembali video pelajaran terkait dan mengulang pengerjaan kuis.');

$docBody .= h2('2.6 Klaim, Unduh, dan Cetak Sertifikat Digital');
$docBody .= p('Setelah seluruh materi video ditonton (100%) dan seluruh kuis evaluasi berhasil lulus:');
$docBody .= bullet('Kartu sertifikat di sidebar silabus otomatis beralih dari status "TERKUNCI" menjadi "LULUS".');
$docBody .= bullet('Sistem menampilkan jendela pop-up perayaan kelulusan dan mengaktifkan tombol "Lihat & Cetak Sertifikat".');
$docBody .= bullet('Pada halaman sertifikat resmi, Anda dapat mencetak dokumen langsung ke printer atau menyimpannya sebagai berkas PDF beresolusi tinggi.');
$docBody .= bullet('Sertifikat dilengkapi Kode Registrasi Unik dan QR Code resmi yang dapat diverifikasi keasliannya oleh siapapun secara publik.');

$docBody .= pageBreak();

// ==================== BAB 3: PANDUAN ADMIN & GURU ====================
$docBody .= h1('Bab 3: Panduan Admin Sekolah & Instruktur (Teacher Journey)');
$docBody .= p('Instruktur dan Admin Sekolah memiliki hak istimewa untuk mengelola kurikulum sekolah, mengunggah materi berbasis YouTube, menyusun soal kuis checkpoint, melampirkan berkas modul, serta mengadministrasi data siswa.');

$docBody .= h2('3.1 Dasbor Admin Sekolah & Ringkasan Metrik Pembelajaran');
$docBody .= p('Setelah masuk dengan akun Admin Sekolah, Anda akan disambut oleh Dasbor Analitik yang memberikan pandangan menyeluruh terhadap efektivitas belajar di sekolah Anda:');
$docBody .= bullet('Total Video Pelajaran Aktif:', 0, '•', 'Menampilkan jumlah materi video yang telah tersinkronisasi dan dapat diakses siswa.');
$docBody .= bullet('Total Kursus Terpublikasi:', 0, '•', 'Menghitung kursus yang sudah siap dipelajari dibandingkan kursus yang masih berupa draf.');
$docBody .= bullet('Akumulasi Jam Belajar Siswa:', 0, '•', 'Metrik jam tonton kumulatif seluruh siswa di sekolah Anda untuk mengukur tingkat keaktifan.');
$docBody .= bullet('Status YouTube Player Service:', 0, '•', 'Indikator kesehatan koneksi API video dan validitas penayangan.');

// GAMBAR 3
$docBody .= imageDrawing('rId4', 5400000, 4320000, 'Gambar 3.1: Dasbor Utama Admin dan Pengelola Video Pembelajaran', 3);

$docBody .= h2('3.2 Manajemen Kursus & Pembuatan Kursus Baru');
$docBody .= p('Untuk membuat kursus baru bagi siswa sekolah Anda:');
$docBody .= bullet('Buka menu "Manajer Kursus" di bilah navigasi kiri, lalu klik tombol "+ Buat Kursus Baru".', 0, '1.');
$docBody .= bullet('Isi Formulir Identitas Kursus:', 0, '2.', 'Ketik Judul Kursus, Deskripsi Lengkap, dan pilih Kategori Keilmuan yang sesuai.');
$docBody .= bullet('Pilih Tingkat Kesulitan:', 0, '3.', 'Tentukan level materi: Pemula (Beginner), Menengah (Intermediate), atau Mahir (Advanced).');
$docBody .= bullet('Unggah Gambar Sampul (Thumbnail):', 0, '4.', 'Pilih gambar beresolusi 1280x720 piksel (rasio 16:9) yang mewakili topik pembelajaran.');
$docBody .= bullet('Simpan Kursus:', 0, '5.', 'Klik "Simpan Kursus". Kursus baru otomatis terbentuk dan siap untuk diisi modul serta materi video.');

$docBody .= h2('3.3 Menambahkan Materi Video dengan Impor YouTube Cepat');
$docBody .= p('Fitur Impor YouTube Cepat adalah salah satu keunggulan terbesar EduTube LMS. Anda tidak perlu mengunggah berkas video berukuran gigabyte:');
$docBody .= bullet('Pilih Kursus dan Modul Pembelajaran target.', 0, '1.');
$docBody .= bullet('Tempelkan Tautan (URL) Video YouTube:', 0, '2.', 'Sistem mendukung seluruh varian tautan YouTube (https://www.youtube.com/watch?v=..., https://youtu.be/..., maupun format YouTube Shorts).');
$docBody .= bullet('Klik Tombol "Ambil Otomatis":', 0, '3.', 'Sistem seketika memproses tautan, mengekstrak 11 karakter unik Video ID, menarik thumbnail kualitas HD, dan mendeteksi judul materi.');
$docBody .= bullet('Tulis Catatan / Rangkuman Modul:', 0, '4.', 'Lengkapi penjelasan materi yang akan tampil di bawah video bagi siswa.');
$docBody .= bullet('Tentukan Status Publikasi:', 0, '5.', 'Pilih "Publikasikan" agar langsung dapat diakses siswa, atau "Draf" bila materi masih dalam tahap penyusunan.');

// GAMBAR 4
$docBody .= imageDrawing('rId5', 5400000, 4320000, 'Gambar 3.2: Modal Impor Video YouTube Cepat dan Otomatisasi Metadata', 4);

$docBody .= h2('3.4 Penyusun Kurikulum (Single-Page Course Builder) & Modul Bertingkat');
$docBody .= p('Melalui antarmuka Penyusun Kurikulum (/admin/curriculum), instruktur dapat menyusun peta jalan belajar yang terstruktur:');
$docBody .= bullet('Membuat Modul Tematik:', 0, '•', 'Kelompokkan materi ke dalam bab-bab logis (contoh: Bab 1: Dasar HTML & CSS, Bab 2: JavaScript Modern, Bab 3: Proyek Akhir).');
$docBody .= bullet('Mengatur Urutan Materi Pembelajaran:', 0, '•', 'Gunakan kontrol posisi untuk mengurutkan materi dari materi dasar hingga materi lanjutan.');
$docBody .= bullet('Meninjau Durasi Belajar Modul:', 0, '•', 'Sistem secara otomatis menjumlahkan total durasi video dalam setiap bab untuk memberikan estimasi waktu belajar kepada siswa.');

$docBody .= h2('3.5 Pembuatan Kuis Evaluasi, Bank Soal, & Batas Nilai Kelulusan');
$docBody .= p('Untuk menjamin pemahaman siswa terhadap materi yang diajarkan, instruktur dapat menambahkan kuis evaluasi:');
$docBody .= bullet('Pilih Pelajaran Target dan Buka Tab Kuis:', 0, '1.', 'Di antarmuka Course Builder atau Kurikulum, pilih tombol kuis materi.');
$docBody .= bullet('Tetapkan Batas Nilai Kelulusan (Passing Grade):', 0, '2.', 'Tentukan ambang batas nilai minimal (standar: 75% atau 80%). Siswa wajib mencapai nilai ini agar materi kuis dinyatakan tuntas.');
$docBody .= bullet('Aktifkan Opsi Acak Soal (Shuffle):', 0, '3.', 'Fitur ini mengacak urutan nomor soal dan opsi jawaban bagi masing-masing siswa untuk meminimalisir kemungkinan saling menyontek.');
$docBody .= bullet('Tambahkan Butir Soal Pilihan Ganda:', 0, '4.', 'Tuliskan teks pertanyaan, isi opsi jawaban A, B, C, D, centang salah satu sebagai kunci jawaban yang benar, dan sertakan teks pembahasan untuk memfasilitasi proses remedial mandiri.');

$docBody .= h2('3.6 Manajemen Berkas Sumber Daya & Lampiran Modul (Lesson Resources)');
$docBody .= p('Selain video YouTube, pengajar dapat melampirkan berkas pendukung langsung pada setiap materi pelajaran:');
$docBody .= bullet('Menambah Sumber Daya Baru:', 0, '•', 'Klik "+ Tambah Lampiran", ketik judul lampiran (contoh: "Panduan Praktikum CSS Flexbox.pdf" atau "Repositori Starter Github"), lalu masukkan tautan unduh atau berkas yang relevan.');
$docBody .= bullet('Pengelolaan Lampiran:', 0, '•', 'Lampiran dapat diedit atau dihapus kapan saja jika terdapat pembaruan silabus.');

$docBody .= h2('3.7 Administrasi Siswa: Pendaftaran Manual, Impor CSV, & Ekspor Data');
$docBody .= p('Admin Sekolah bertanggung jawab atas administrasi akun siswa di sekolahnya:');
$docBody .= bullet('Pendaftaran Siswa Mandiri:', 0, '1.', 'Buka menu "Siswa / Mahasiswa", klik "+ Tambah Siswa Baru", isi nama lengkap, NIS/NIM, alamat surel, dan kata sandi sementara.');
$docBody .= bullet('Impor Massal via CSV (Fitur Unggulan):', 0, '2.', 'Untuk mendaftarkan satu kelas atau satu angkatan sekaligus, klik "Unduh Template CSV", isi berkas spreadsheet dengan kolom [name, email, password], lalu unggah kembali berkas CSV tersebut. Ratusan akun siswa terdaftar dalam waktu hitungan detik.');
$docBody .= bullet('Ekspor Data Siswa:', 0, '3.', 'Klik tombol "Ekspor CSV" untuk mengunduh rekapitulasi data seluruh siswa dan progres belajarnya ke format Excel.');
$docBody .= bullet('Reset Kata Sandi Siswa:', 0, '4.', 'Bila ada siswa yang lupa kata sandi, admin sekolah cukup mengklik tombol "Reset Password" di baris nama siswa terkait.');

$docBody .= h2('3.8 Pusat Dukungan Sekolah & Pengajuan Tiket Bantuan (School Support)');
$docBody .= p('Jika menemui kendala teknis atau memiliki permohonan fitur baru:');
$docBody .= bullet('Buka menu "Dukungan & Log" pada bilah navigasi admin.');
$docBody .= bullet('Klik tombol "Buat Tiket Bantuan", pilih kategori kendala (Teknis, Kurikulum, atau Akun), tulis judul dan rincian masalah, lalu kirimkan.');
$docBody .= bullet('Tiket akan langsung masuk ke antrean Super Admin Pusat dan setiap tanggapan resmi dapat dipantau riwayatnya di halaman tersebut.');

$docBody .= pageBreak();

// ==================== BAB 4: PANDUAN SUPER ADMIN ====================
$docBody .= h1('Bab 4: Panduan untuk Super Administrator (Platform Management)');
$docBody .= p('Peran Super Administrator memegang kendali tertinggi atas seluruh infrastruktur platform EduTube LMS, melayani pengelolaan multi-sekolah, master kategori global, konfigurasi keamanan, dan penanganan tiket pusat.');

$docBody .= h2('4.1 Dasbor Pengawasan Global & Metrik Ekosistem Multi-Sekolah');
$docBody .= p('Dasbor Super Admin dirancang untuk menyajikan status kesehatan ekosistem platform secara makro:');
$docBody .= bullet('Agregat Sekolah Mitra Aktif:', 0, '•', 'Jumlah institusi pendidikan yang saat ini aktif beroperasi menggunakan platform.');
$docBody .= bullet('Populasi Pengguna Global:', 0, '•', 'Total gabungan Super Admin, Admin Sekolah, Guru, dan Siswa di seluruh lembaga.');
$docBody .= bullet('Volume Konten & Jam Belajar:', 0, '•', 'Total kumulatif video pelajaran dan jam waktu tonton yang telah dilayani oleh platform.');
$docBody .= bullet('Status Antrean Tiket Bantuan:', 0, '•', 'Jumlah tiket kendala dari sekolah yang memerlukan tanggapan dari tim pengelola pusat.');

$docBody .= h2('4.2 Manajemen Master Sekolah Mitra (Multi-Tenancy)');
$docBody .= p('Super Admin mengatur ketersediaan layanan untuk masing-masing institusi mitra melalui menu Master Sekolah (/admin/schools):');
$docBody .= bullet('Pendaftaran Sekolah Baru:', 0, '1.', 'Klik "+ Tambah Sekolah", masukkan Nama Resmi Lembaga (contoh: "SMK Negeri 1 Denpasar"), Nomor Pokok Sekolah Nasional (NPSN), alamat lengkap, email kontak resmi, dan unggah logo sekolah.');
$docBody .= bullet('Pengaturan Status Keaktifan:', 0, '2.', 'Super Admin dapat mengubah status sekolah menjadi "Aktif" atau "Ditangguhkan" (Suspend) sesuai perjanjian kerja sama.');
$docBody .= bullet('Perubahan Informasi Lembaga:', 0, '3.', 'Profil dan data kontak sekolah mitra dapat diperbarui kapan saja melalui tombol "Ubah Data".');

$docBody .= h2('4.3 Manajemen Pengguna Global & Penetapan Hak Akses (User Management)');
$docBody .= p('Melalui menu Manajemen Pengguna (/admin/users), Super Admin memiliki kendali penuh terhadap seluruh akun pengguna:');
$docBody .= bullet('Pembuatan Akun Admin Sekolah:', 0, '1.', 'Klik "+ Tambah Admin Sekolah", masukkan identitas akun, dan tautkan akun tersebut ke sekolah mitra yang bersangkutan.');
$docBody .= bullet('Penetapan Peran (Role Assignment):', 0, '2.', 'Super Admin dapat mengubah wewenang pengguna (misalnya menaikkan akun Guru menjadi Admin Sekolah).');
$docBody .= bullet('Aktivasi & Penonaktifan Akun:', 0, '3.', 'Akun yang melanggar ketentuan dapat dinonaktifkan seketika tanpa menghapus riwayat belajar yang bersangkutan.');
$docBody .= bullet('Reset Akses Keamanan:', 0, '4.', 'Fitur pemulihan kata sandi cepat untuk akun tingkat tinggi yang kehilangan kredensial login.');

$docBody .= h2('4.4 Manajemen Master Kategori Kursus');
$docBody .= p('Taksonomi keilmuan kursus diatur terpusat oleh Super Admin di menu Kategori Kursus (/admin/categories):');
$docBody .= bullet('Standarisasi Kategori:', 0, '•', 'Mencegah duplikasi nama kategori dengan menetapkan kategori baku (contoh: Rekayasa Perangkat Lunak, Desain Komunikasi Visual, Cloud Computing, dsb.).');
$docBody .= bullet('Penetapan Visual:', 0, '•', 'Setiap kategori dilengkapi dengan nama slug URL, ikon visual Material Symbols, dan warna tema unik untuk memudahkan navigasi siswa.');
$docBody .= bullet('Struktur Hierarki:', 0, '•', 'Kategori dapat dikaitkan dengan kursus lintas sekolah mitra.');

$docBody .= h2('4.5 Pengaturan Konfigurasi Platform Global');
$docBody .= p('Di menu Pengaturan Sistem (/admin/settings), Super Admin mengontrol parameter global aplikasi:');
$docBody .= bullet('Identitas Platform:', 0, '•', 'Nama resmi platform pembelajaran, favicon browser, logo utama, dan teks hak cipta.');
$docBody .= bullet('Kebijakan Sesi & Keamanan:', 0, '•', 'Batas waktu kedaluwarsa sesi login pengguna (*session lifetime*) dan aturan kompleksitas kata sandi.');
$docBody .= bullet('Informasi Bantuan Pusat:', 0, '•', 'Surel dukungan teknis, nomor WhatsApp pengaduan, dan jam operasional helpdesk.');

$docBody .= h2('4.6 Helpdesk Pusat, Penanganan Tiket, & Diagnostik Sistem Server');
$docBody .= p('Melalui menu Dukungan & Log (/admin/support), tim pusat menjaga kestabilan operasional platform:');
$docBody .= bullet('Kotak Masuk Tiket Pusat:', 0, '•', 'Menerima dan membalas keluhan dari administrator sekolah mitra secara resmi.');
$docBody .= bullet('Panel Diagnostik Teknis:', 0, '•', 'Memantau versi runtime PHP, versi framework Laravel, tipe driver database MySQL, status caching, dan latensi pemutar video YouTube.');

$docBody .= pageBreak();

// ==================== BAB 5: KETENTUAN SERTIFIKAT KELULUSAN ====================
$docBody .= h1('Bab 5: Ketentuan Penerbitan Sertifikat Kelulusan');
$docBody .= p('EduTube LMS menerapkan standar mutu kelulusan yang ketat dan transparan. Sertifikat kelulusan tidak diberikan semata-mata karena siswa terdaftar, melainkan sebagai bukti kompetensi nyata bahwa siswa telah menyimak seluruh materi dan lulus ujian.');

$docBody .= callout('ATURAN RESMI PEMBUKAAN SERTIFIKAT KELULUSAN', 'Sertifikat Kelulusan HANYA AKAN TERBUKA dan diterbitkan oleh sistem jika memenuhi DUA SYARAT MUTLAK secara bersamaan:\n1. Siswa telah menonton dan menyelesaikan 100% video materi dalam kursus tersebut.\n2. Siswa telah mengerjakan dan DINYATAKAN LULUS pada seluruh kuis evaluasi materi dan kuis kursus.', 'warning');

$docBody .= h2('5.1 Dua Syarat Mutlak Penerbitan Sertifikat');
$docBody .= p('Untuk mendapatkan sertifikat kelulusan, siswa wajib menuntaskan dua pilar evaluasi berikut:');
$docBody .= bullet('Pilar 1: Penyelesaian Seluruh Video Materi (100% Video Progress):', 0, '1.', 'Setiap pelajaran video dalam seluruh modul harus ditonton hingga selesai dan berstatus centang hijau.');
$docBody .= bullet('Pilar 2: Kelulusan Seluruh Kuis Checkpoint (100% Quiz Passing):', 0, '2.', 'Setiap kuis evaluasi materi yang dirancang oleh instruktur harus dikerjakan dengan perolehan nilai sama dengan atau di atas passing grade (batas kelulusan). Jika terdapat 1 saja kuis yang belum lulus, sertifikat tetap terkunci.');

$docBody .= h2('5.2 Checklist Transparan pada Silabus Kelas');
$docBody .= p('Ruang belajar siswa menampilkan kartu status kelulusan yang dinamis di bagian atas sidebar silabus:');
$docBody .= bullet('Kondisi Terkunci (Locked State):', 0, '•', 'Tampil status "TERKUNCI" berwarna kuning/abu-abu dengan rincian persentase: jumlah video tersisa (misal: "4 dari 7 Video Selesai") dan jumlah kuis yang belum lulus (misal: "0 dari 1 Kuis Lulus"). Siswa mengetahui secara pasti hal apa yang masih harus diselesaikan.');
$docBody .= bullet('Kondisi Terbuka (Unlocked / Passed State):', 0, '•', 'Begitu syarat terakhir terpenuhi, kartu seketika berubah menjadi hijau cerah dengan lencana "LULUS" dan tombol aksi aktif "Lihat & Cetak Sertifikat Kelulusan". Selain itu, jendela perayaan kelulusan akan muncul di layar siswa.');

$docBody .= h2('5.3 Standar Keamanan & Verifikasi Sertifikat Resmi');
$docBody .= p('Setiap sertifikat digital yang diterbitkan oleh EduTube LMS dilindungi oleh tiga lapis pengamanan:');
$docBody .= bullet('Nomor Registrasi Sertifikat Unik:', 0, '•', 'Setiap lembar sertifikat memiliki kode alfanumerik acak anti-duplikasi (contoh: CERT-92HP3CFPLY) yang tercatat permanen di database pusat.');
$docBody .= bullet('Kode QR Dinamis (Verifikasi Publik):', 0, '•', 'Sertifikat memuat kode QR yang dapat dipindai oleh pihak eksternal (kampus, sekolah, maupun calon perusahaan kerja) menggunakan kamera ponsel pintar tanpa perlu login untuk memverifikasi keaslian nama pemilik, nama kursus, dan tanggal kelulusan.');
$docBody .= bullet('Format Cetak Berstandar Dokumen Resmi:', 0, '•', 'Tata letak sertifikat dirancang profesional dengan perpaduan bingkai elegan, tipografi resmi, logo institusi sekolah, dan tanda tangan digital penanggung jawab.');

$docBody .= pageBreak();

// ==================== BAB 6: FAQ & TROUBLESHOOTING ====================
$docBody .= h1('Bab 6: Tanya Jawab Umum & Solusi Kendala Teknis');
$docBody .= p('Berikut adalah ringkasan pertanyaan yang paling sering diajukan beserta panduan pemecahan masalah teknis:');

$docBody .= h2('6.1 Solusi Video YouTube Tidak Dapat Diputar (Private / Embedding)');
$docBody .= p('Kendala: Layar pemutar video menampilkan pesan error "Video unavailable" atau "Playback on other websites has been disabled by the video owner".');
$docBody .= bullet('Penyebab 1: Video Disetel ke Mode Privat (Private).', 0, '•', 'Solusi: Buka YouTube Studio pemilik video, ubah visibilitas video menjadi "Unlisted" (Tidak Publik) atau "Public" (Publik). Video yang disetel ke Privat tidak dapat disematkan di situs manapun.');
$docBody .= bullet('Penyebab 2: Opsi Embedding Dimatikan.', 0, '•', 'Solusi: Di setelan lanjutan video YouTube Studio, pastikan opsi "Izinkan Penyematan" (Allow Embedding) dalam keadaan dicentang.');

$docBody .= h2('6.2 Solusi Progres 100% Video tetapi Sertifikat Belum Terbit');
$docBody .= p('Kendala: Seluruh video pelajaran telah ditonton dan ditandai selesai, namun tombol cetak sertifikat masih berstatus terkunci.');
$docBody .= p('Solusi: Periksa checklist pada kartu "Syarat Sertifikat" di sidebar kanan ruang belajar. Pastikan seluruh kuis checkpoint atau ujian akhir kursus telah dikerjakan dan memperoleh nilai di atas passing grade. Jika nilai kuis Anda masih di bawah ambang batas lulus, klik tab Kuis dan lakukan pengerjaan ulang (remedial) hingga lulus.');

$docBody .= h2('6.3 Penambahan Kuis di Tengah Kursus yang Sedang Berjalan');
$docBody .= p('Pertanyaan: Apakah guru dapat menambahkan kuis checkpoint baru pada kursus yang sudah memiliki siswa aktif?');
$docBody .= p('Jawaban: Ya. Guru dapat menambahkan modul atau soal kuis kapan saja. Sistem secara otomatis akan memperbarui daftar checklist kelulusan. Siswa yang belum mengklaim sertifikat akan diminta menyelesaikan kuis tambahan tersebut.');

$docBody .= h2('6.4 Verifikasi Keaslian Sertifikat Digital oleh Pihak Ketiga');
$docBody .= p('Pertanyaan: Bagaimana cara perusahaan perekrut atau pihak luar memverifikasi keaslian sertifikat siswa?');
$docBody .= p('Jawaban: Sangat mudah! Pihak ketiga cukup membuka kamera ponsel dan memindai QR Code yang tercetak di sudut bawah lembar sertifikat, atau langsung mengunjungi tautan URL publik: https://domain-lms/sertifikat/{kode}. Halaman verifikasi publik akan menampilkan data sah pemilik sertifikat tanpa meminta login.');

$docBody .= h2('6.5 Panduan Reset Kata Sandi Siswa dan Admin');
$docBody .= p('Kendala: Siswa atau staf guru lupa kata sandi akunnya.');
$docBody .= bullet('Bagi Siswa:', 0, '•', 'Hubungi Admin Sekolah Anda. Admin sekolah dapat membuka menu "Siswa / Mahasiswa" dan mengklik tombol "Reset Password" pada baris nama Anda untuk mengatur kata sandi baru.');
$docBody .= bullet('Bagi Admin Sekolah:', 0, '•', 'Hubungi Super Administrator melalui formulir tiket bantuan atau kontak pengurus platform pusat untuk mendapatkan tautan pemulihan kata sandi.');

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
    '.$docBody.'
  </w:body>
</w:document>';

// ==================== BUNDLE ZIP KE FILE .DOCX ====================
if (file_exists($outputDocx)) {
    unlink($outputDocx);
}

$zip = new ZipArchive;
if ($zip->open($outputDocx, ZipArchive::CREATE) !== true) {
    exit("Error: Gagal membuat arsip zip $outputDocx\n");
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
echo 'Lokasi Root: '.$outputDocx.' ('.round(filesize($outputDocx) / 1024, 2)." KB)\n";
echo 'Lokasi Publik: '.$publicDocx."\n";
