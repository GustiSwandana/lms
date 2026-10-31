<?php

/**
 * Generator Buku Panduan Penggunaan Resmi Platform LMS SIAGA BUMI-SSK (.docx)
 * BKKBN Perwakilan Provinsi Sulawesi Utara
 * Menghasilkan dokumen Microsoft Word OpenXML (.docx) resmi lengkap dengan materi dan metrik riil aplikasi:
 * - 3 Kursus Resmi: Mengenai BKKBN, Apa sih SSK?, dan PPKS
 * - 6 Pelajaran Video HD YouTube Terverifikasi
 * - 2 Sekolah Mitra: SMP Negeri 1 Bitung & SRMA 44 Minahasa
 * - Butir Soal Kuis Checkpoint Riil di Database beserta Kunci Jawaban & Pembahasan Pedagogis
 * - 11 Tangkapan Layar (Screenshots) Sistem Asli
 */
$rootDir = __DIR__;
$outputDocx = $rootDir.'/Panduan_Penggunaan_SiagaBumi_SSK.docx';
$publicDocxDir = $rootDir.'/public/docs';
if (! is_dir($publicDocxDir)) {
    mkdir($publicDocxDir, 0755, true);
}
$publicDocx = $publicDocxDir.'/Panduan_Penggunaan_SiagaBumi_SSK.docx';
$artifactDir = 'C:/Users/gusti/.gemini/antigravity/brain/86519e43-2ec3-4137-97ab-bd5701e36cce';
$artifactDocx = $artifactDir.'/Panduan_Penggunaan_SiagaBumi_SSK.docx';

// 11 Gambar Screenshot Resmi
$images = [
    'img1' => $rootDir.'/docs/screenshots/01-beranda-katalog.png',
    'img2' => $rootDir.'/docs/screenshots/02-halaman-masuk.png',
    'img3' => $rootDir.'/docs/screenshots/03-pendaftaran-siswa.png',
    'img4' => $rootDir.'/docs/screenshots/04-dasbor-siswa.png',
    'img5' => $rootDir.'/docs/screenshots/05-ruang-belajar-video.png',
    'img6' => $rootDir.'/docs/screenshots/06-catatan-waktu.png',
    'img7' => $rootDir.'/docs/screenshots/07-kuis-checkpoint.png',
    'img8' => $rootDir.'/docs/screenshots/08-verifikasi-sertifikat.png',
    'img9' => $rootDir.'/docs/screenshots/09-dasbor-admin.png',
    'img10' => $rootDir.'/docs/screenshots/10-course-builder.png',
    'img11' => $rootDir.'/docs/screenshots/11-manajemen-siswa.png',
];

foreach ($images as $key => $path) {
    if (! file_exists($path)) {
        exit("Error: Berkas gambar $path tidak ditemukan!\n");
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
  <Relationship Id="rId6" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/image" Target="media/image5.png"/>
  <Relationship Id="rId7" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/image" Target="media/image6.png"/>
  <Relationship Id="rId8" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/image" Target="media/image7.png"/>
  <Relationship Id="rId9" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/image" Target="media/image8.png"/>
  <Relationship Id="rId10" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/image" Target="media/image9.png"/>
  <Relationship Id="rId11" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/image" Target="media/image10.png"/>
  <Relationship Id="rId12" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/image" Target="media/image11.png"/>
</Relationships>';

// 4. word/styles.xml
$stylesXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<w:styles xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main">
  <w:docDefaults>
    <w:rPrDefault>
      <w:rPr>
        <w:rFonts w:ascii="Plus Jakarta Sans" w:hAnsi="Plus Jakarta Sans" w:cs="Arial"/>
        <w:sz w:val="22"/>
        <w:color w:val="334155"/>
        <w:lang w:val="id-ID"/>
      </w:rPr>
    </w:rPrDefault>
    <w:pPrDefault>
      <w:pPr>
        <w:spacing w:line="280" w:lineRule="auto" w:after="140"/>
      </w:pPr>
    </w:pPrDefault>
  </w:docDefaults>

  <w:style w:type="paragraph" w:styleId="Title">
    <w:name w:val="Title"/>
    <w:pPr>
      <w:jc w:val="center"/>
      <w:spacing w:before="600" w:after="200"/>
    </w:pPr>
    <w:rPr>
      <w:rFonts w:ascii="Plus Jakarta Sans" w:hAnsi="Plus Jakarta Sans"/>
      <w:b/>
      <w:sz w:val="56"/>
      <w:color w:val="0F172A"/>
    </w:rPr>
  </w:style>

  <w:style w:type="paragraph" w:styleId="Heading1">
    <w:name w:val="heading 1"/>
    <w:pPr>
      <w:spacing w:before="440" w:after="160"/>
      <w:keepNext/>
    </w:pPr>
    <w:rPr>
      <w:rFonts w:ascii="Plus Jakarta Sans" w:hAnsi="Plus Jakarta Sans"/>
      <w:b/>
      <w:sz w:val="34"/>
      <w:color w:val="065F46"/>
    </w:rPr>
  </w:style>

  <w:style w:type="paragraph" w:styleId="Heading2">
    <w:name w:val="heading 2"/>
    <w:pPr>
      <w:spacing w:before="320" w:after="120"/>
      <w:keepNext/>
    </w:pPr>
    <w:rPr>
      <w:rFonts w:ascii="Plus Jakarta Sans" w:hAnsi="Plus Jakarta Sans"/>
      <w:b/>
      <w:sz w:val="28"/>
      <w:color w:val="1E3A8A"/>
    </w:rPr>
  </w:style>

  <w:style w:type="paragraph" w:styleId="Heading3">
    <w:name w:val="heading 3"/>
    <w:pPr>
      <w:spacing w:before="220" w:after="80"/>
      <w:keepNext/>
    </w:pPr>
    <w:rPr>
      <w:rFonts w:ascii="Plus Jakarta Sans" w:hAnsi="Plus Jakarta Sans"/>
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
        <w:spacing w:before=\"$before\" w:after=\"$after\" w:line=\"280\" w:lineRule=\"auto\"/>
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
        <w:spacing w:before="460" w:after="180"/>
        <w:pBdr>
          <w:bottom w:val="single" w:sz="18" w:space="6" w:color="059669"/>
        </w:pBdr>
        <w:keepNext/>
      </w:pPr>
      <w:r>
        <w:rPr>
          <w:rFonts w:ascii="Plus Jakarta Sans" w:hAnsi="Plus Jakarta Sans"/>
          <w:b/>
          <w:sz w:val="34"/>
          <w:color w:val="065F46"/>
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
        <w:spacing w:before="320" w:after="140"/>
        <w:keepNext/>
      </w:pPr>
      <w:r>
        <w:rPr>
          <w:rFonts w:ascii="Plus Jakarta Sans" w:hAnsi="Plus Jakarta Sans"/>
          <w:b/>
          <w:sz w:val="28"/>
          <w:color w:val="1E3A8A"/>
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
        <w:spacing w:before="220" w:after="80"/>
        <w:keepNext/>
      </w:pPr>
      <w:r>
        <w:rPr>
          <w:rFonts w:ascii="Plus Jakarta Sans" w:hAnsi="Plus Jakarta Sans"/>
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
        <w:spacing w:before=\"40\" w:after=\"80\" w:line=\"280\" w:lineRule=\"auto\"/>
      </w:pPr>
      <w:r>
        <w:rPr>
          <w:rFonts w:ascii=\"Arial\" w:hAnsi=\"Arial\"/>
          <w:color w:val=\"059669\"/>
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
        'success' => '059669',
        'warning' => 'D97706',
        'alert' => 'E11D48',
        default => '4F46E5',
    };
    $bgColor = match ($type) {
        'success' => 'ECFDF5',
        'warning' => 'FFFBEB',
        'alert' => 'FFF1F2',
        default => 'EEF2FF',
    };

    $normalized = str_replace(["\r\n", "\r"], "\n", $content);
    $lines = explode("\n", $normalized);
    $contentParagraphs = '';
    foreach ($lines as $line) {
        $contentParagraphs .= '<w:p>
            <w:pPr><w:spacing w:before="0" w:after="50"/></w:pPr>
            <w:r>
              <w:rPr><w:color w:val="334155"/><w:sz w:val="21"/></w:rPr>
              <w:t xml:space="preserve">'.escapeXml($line).'</w:t>
            </w:r>
          </w:p>';
    }

    return "<w:tbl>
      <w:tblPr>
        <w:tblW w:w=\"9360\" w:type=\"dxa\"/>
        <w:tblBorders>
          <w:top w:val=\"none\"/>
          <w:left w:val=\"single\" w:sz=\"28\" w:space=\"0\" w:color=\"$borderColor\"/>
          <w:bottom w:val=\"none\"/>
          <w:right w:val=\"none\"/>
        </w:tblBorders>
        <w:tblCellMar>
          <w:top w:w=\"160\" w:type=\"dxa\"/>
          <w:left w:w=\"260\" w:type=\"dxa\"/>
          <w:bottom w:w=\"160\" w:type=\"dxa\"/>
          <w:right w:w=\"260\" w:type=\"dxa\"/>
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
    <w:p><w:pPr><w:spacing w:before=\"0\" w:after=\"120\"/></w:pPr></w:p>";
}

function imageDrawing($relId, $caption = '', $imgId = 1)
{
    // 6.0 inci lebar = 5486400 EMUs, rasio 1280:800 (0.625) = 3429000 EMUs
    $cx = 5486400;
    $cy = 3429000;

    $imgXml = "<w:p>
      <w:pPr>
        <w:jc w:val=\"center\"/>
        <w:spacing w:before=\"180\" w:after=\"80\"/>
        <w:keepNext/>
      </w:pPr>
      <w:r>
        <w:drawing>
          <wp:inline distT=\"0\" distB=\"0\" distL=\"0\" distR=\"0\">
            <wp:extent cx=\"$cx\" cy=\"$cy\"/>
            <wp:effectExtent l=\"0\" t=\"0\" r=\"0\" b=\"0\"/>
            <wp:docPr id=\"$imgId\" name=\"Gambar $imgId\"/>
            <wp:cNvGraphicFramePr>
              <a:graphicFrameLocks noChangeAspect=\"1\"/>
            </wp:cNvGraphicFramePr>
            <a:graphic>
              <a:graphicData uri=\"http://schemas.openxmlformats.org/drawingml/2006/picture\">
                <pic:pic xmlns:pic=\"http://schemas.openxmlformats.org/drawingml/2006/picture\">
                  <pic:nvPicPr>
                    <pic:cNvPr id=\"$imgId\" name=\"Screenshot $imgId\"/>
                    <pic:cNvPicPr/>
                  </pic:nvPicPr>
                  <pic:blipFill>
                    <a:blip r:embed=\"$relId\"/>
                    <a:stretch><a:fillRect/></a:stretch>
                  </pic:blipFill>
                  <pic:spPr>
                    <a:xfrm><a:off x=\"0\" y=\"0\"/><a:ext cx=\"$cx\" cy=\"$cy\"/></a:xfrm>
                    <a:prstGeom prst=\"rect\"><a:avLst/></a:prstGeom>
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
            <w:spacing w:before="40" w:after="220"/>
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

function tableRbac()
{
    $rows = [
        ['Peran (Role)', 'Cakupan Hak Akses', 'Fitur Utama yang Dikelola'],
        ['Admin Pusat (Super Admin)', 'Provinsi & Multi-Sekolah BKKBN', 'Kelola sekolah percontohan, akun operator, kurasi visibilitas tamu (is_visible_to_guest), setelan global, tiket pengaduan pusat.'],
        ['Operator Sekolah', 'Tingkat Satuan Pendidikan', 'Pendaftaran siswa baru satuan & impor masal CSV, reset kata sandi akun siswa, rekap kelulusan sekolah.'],
        ['Guru / Pendidik', 'Pengelolaan Modul & Evaluasi', 'Unified Course Builder, impor video YouTube otomatis, manajemen silabus bab, bank soal kuis, kunci jawaban & pembahasan.'],
        ['Siswa Pembelajar', 'Portal Belajar & Sertifikasi', 'Katalog kursus 3 pilar, pemutar video YouTube bebas iklan, live tracking 90%, catatan waktu interaktif, kuis checkpoint (passing grade 70%), klaim sertifikat digital QR Code.'],
        ['Penyuluh / Tamu', 'Mode Galeri (View-Only)', 'Akses peninjauan materi terkurasi publik, materi paparan penyuluhan lapangan tanpa hak mengerjakan kuis atau memodifikasi data.'],
    ];

    $html = '<w:tbl>
      <w:tblPr>
        <w:tblW w:w="9360" w:type="dxa"/>
        <w:tblBorders>
          <w:top w:val="single" w:sz="6" w:space="0" w:color="CBD5E1"/>
          <w:left w:val="single" w:sz="6" w:space="0" w:color="CBD5E1"/>
          <w:bottom w:val="single" w:sz="6" w:space="0" w:color="CBD5E1"/>
          <w:right w:val="single" w:sz="6" w:space="0" w:color="CBD5E1"/>
          <w:insideH w:val="single" w:sz="4" w:space="0" w:color="E2E8F0"/>
          <w:insideV w:val="single" w:sz="4" w:space="0" w:color="E2E8F0"/>
        </w:tblBorders>
        <w:tblCellMar>
          <w:top w:w="120" w:type="dxa"/>
          <w:left w:w="160" w:type="dxa"/>
          <w:bottom w:w="120" w:type="dxa"/>
          <w:right w:w="160" w:type="dxa"/>
        </w:tblCellMar>
      </w:tblPr>';

    foreach ($rows as $idx => $cols) {
        $isHeader = ($idx === 0);
        $bg = $isHeader ? 'ECFDF5' : ($idx % 2 === 1 ? 'FFFFFF' : 'F8FAFC');
        $html .= '<w:tr>';
        foreach ($cols as $cIdx => $cell) {
            $w = ($cIdx === 0) ? '2300' : (($cIdx === 1) ? '2800' : '4260');
            $b = $isHeader ? '<w:b/>' : '';
            $color = $isHeader ? '065F46' : '334155';
            $html .= "<w:tc>
              <w:tcPr>
                <w:tcW w:w=\"$w\" w:type=\"dxa\"/>
                <w:shd w:val=\"clear\" w:color=\"auto\" w:fill=\"$bg\"/>
              </w:tcPr>
              <w:p>
                <w:pPr><w:spacing w:before=\"40\" w:after=\"40\"/></w:pPr>
                <w:r>
                  <w:rPr>$b<w:color w:val=\"$color\"/><w:sz w:val=\"20\"/></w:rPr>
                  <w:t>".escapeXml($cell).'</w:t>
                </w:r>
              </w:p>
            </w:tc>';
        }
        $html .= '</w:tr>';
    }
    $html .= '</w:tbl><w:p><w:pPr><w:spacing w:before="0" w:after="160"/></w:pPr></w:p>';

    return $html;
}

function tableCredentials()
{
    $rows = [
        ['Peran Akun', 'Alamat Email', 'Kata Sandi', 'Satuan Sekolah / Unit Kerja'],
        ['Admin Pusat', 'admin@edutube.org', 'password', 'BKKBN Perwakilan Prov. Sulawesi Utara'],
        ['Admin Pusat (Cadangan)', 'adminpusat@siagabumi.id', 'password', 'Koordinator Teknis Sistem Informasi'],
        ['Operator Sekolah', 'smkn1@edutube.org', 'password', 'SMP Negeri 1 Bitung'],
        ['Guru / Pendidik', 'guru.minahasa@siagabumi.id', 'password', 'SRMA 44 Minahasa (Pengajar SSK)'],
        ['Guru / Pendidik (2)', 'guru.bitung@sekolah.sch.id', 'password', 'SMP Negeri 1 Bitung (Pengajar PPKS)'],
        ['Siswa Pembelajar', 'student@edutube.org', 'password', 'SMP Negeri 1 Bitung (Aktif)'],
        ['Siswa Pembelajar (2)', 'budi@edutube.org', 'password', 'SMP Negeri 1 Bitung (Lulus Kursus BKKBN)'],
        ['Penyuluh / Tamu', 'guest@edutube.org', 'password', 'Petugas Lapangan BKKBN (Mode Galeri)'],
    ];

    $html = '<w:tbl>
      <w:tblPr>
        <w:tblW w:w="9360" w:type="dxa"/>
        <w:tblBorders>
          <w:top w:val="single" w:sz="6" w:space="0" w:color="CBD5E1"/>
          <w:left w:val="single" w:sz="6" w:space="0" w:color="CBD5E1"/>
          <w:bottom w:val="single" w:sz="6" w:space="0" w:color="CBD5E1"/>
          <w:right w:val="single" w:sz="6" w:space="0" w:color="CBD5E1"/>
          <w:insideH w:val="single" w:sz="4" w:space="0" w:color="E2E8F0"/>
          <w:insideV w:val="single" w:sz="4" w:space="0" w:color="E2E8F0"/>
        </w:tblBorders>
        <w:tblCellMar>
          <w:top w:w="120" w:type="dxa"/>
          <w:left w:w="160" w:type="dxa"/>
          <w:bottom w:w="120" w:type="dxa"/>
          <w:right w:w="160" w:type="dxa"/>
        </w:tblCellMar>
      </w:tblPr>';

    foreach ($rows as $idx => $cols) {
        $isHeader = ($idx === 0);
        $bg = $isHeader ? 'ECFDF5' : ($idx % 2 === 1 ? 'FFFFFF' : 'F8FAFC');
        $html .= '<w:tr>';
        foreach ($cols as $cIdx => $cell) {
            $w = ($cIdx === 0) ? '2100' : (($cIdx === 1) ? '2900' : (($cIdx === 2) ? '1500' : '2860'));
            $b = $isHeader ? '<w:b/>' : '';
            $color = $isHeader ? '065F46' : '334155';
            $html .= "<w:tc>
              <w:tcPr>
                <w:tcW w:w=\"$w\" w:type=\"dxa\"/>
                <w:shd w:val=\"clear\" w:color=\"auto\" w:fill=\"$bg\"/>
              </w:tcPr>
              <w:p>
                <w:pPr><w:spacing w:before=\"40\" w:after=\"40\"/></w:pPr>
                <w:r>
                  <w:rPr>$b<w:color w:val=\"$color\"/><w:sz w:val=\"20\"/></w:rPr>
                  <w:t>".escapeXml($cell).'</w:t>
                </w:r>
              </w:p>
            </w:tc>';
        }
        $html .= '</w:tr>';
    }
    $html .= '</w:tbl><w:p><w:pPr><w:spacing w:before="0" w:after="160"/></w:pPr></w:p>';

    return $html;
}

function tableCoursesMaster()
{
    $rows = [
        ['Judul Kursus Resmi', 'Pilar Kurikulum', 'Satuan Sekolah Pengampu', 'Guru Pengampu / Instruktur', 'Total Modul & Durasi', 'Akses Galeri'],
        ['Mengenai BKKBN', 'Materi Umum Kependudukan', 'SMP Negeri 1 Bitung', 'Drs. Ronald Lumempouw', '2 Video (20:00)', 'Tampil Publik'],
        ['Apa sih SSK?', 'Materi Umum Lingkungan', 'SRMA 44 Minahasa', 'Ibu Martha Runtuwene, S.Pd', '2 Video (20:00)', 'Tertutup Siswa'],
        ['Pencegahan & Penanganan Kekerasan Seksual (PPKS)', 'Kekerasan Sexual', 'SMP Negeri 1 Bitung', 'Dra. Maria Sondakh, M.Si', '2 Video (19:00)', 'Tampil Publik'],
    ];

    $html = '<w:tbl>
      <w:tblPr>
        <w:tblW w:w="9360" w:type="dxa"/>
        <w:tblBorders>
          <w:top w:val="single" w:sz="6" w:space="0" w:color="CBD5E1"/>
          <w:left w:val="single" w:sz="6" w:space="0" w:color="CBD5E1"/>
          <w:bottom w:val="single" w:sz="6" w:space="0" w:color="CBD5E1"/>
          <w:right w:val="single" w:sz="6" w:space="0" w:color="CBD5E1"/>
          <w:insideH w:val="single" w:sz="4" w:space="0" w:color="E2E8F0"/>
          <w:insideV w:val="single" w:sz="4" w:space="0" w:color="E2E8F0"/>
        </w:tblBorders>
        <w:tblCellMar>
          <w:top w:w="120" w:type="dxa"/>
          <w:left w:w="140" w:type="dxa"/>
          <w:bottom w:w="120" w:type="dxa"/>
          <w:right w:w="140" w:type="dxa"/>
        </w:tblCellMar>
      </w:tblPr>';

    foreach ($rows as $idx => $cols) {
        $isHeader = ($idx === 0);
        $bg = $isHeader ? 'ECFDF5' : ($idx % 2 === 1 ? 'FFFFFF' : 'F8FAFC');
        $html .= '<w:tr>';
        foreach ($cols as $cIdx => $cell) {
            $w = match ($cIdx) {
                0 => '2300',
                1 => '1800',
                2 => '1700',
                3 => '1600',
                4 => '1100',
                default => '860',
            };
            $b = $isHeader ? '<w:b/>' : '';
            $color = $isHeader ? '065F46' : '334155';
            $html .= "<w:tc>
              <w:tcPr>
                <w:tcW w:w=\"$w\" w:type=\"dxa\"/>
                <w:shd w:val=\"clear\" w:color=\"auto\" w:fill=\"$bg\"/>
              </w:tcPr>
              <w:p>
                <w:pPr><w:spacing w:before=\"40\" w:after=\"40\"/></w:pPr>
                <w:r>
                  <w:rPr>$b<w:color w:val=\"$color\"/><w:sz w:val=\"19\"/></w:rPr>
                  <w:t>".escapeXml($cell).'</w:t>
                </w:r>
              </w:p>
            </w:tc>';
        }
        $html .= '</w:tr>';
    }
    $html .= '</w:tbl><w:p><w:pPr><w:spacing w:before="0" w:after="160"/></w:pPr></w:p>';

    return $html;
}

function tableLessonsDetails($lessons)
{
    $html = '<w:tbl>
      <w:tblPr>
        <w:tblW w:w="9360" w:type="dxa"/>
        <w:tblBorders>
          <w:top w:val="single" w:sz="6" w:space="0" w:color="CBD5E1"/>
          <w:left w:val="single" w:sz="6" w:space="0" w:color="CBD5E1"/>
          <w:bottom w:val="single" w:sz="6" w:space="0" w:color="CBD5E1"/>
          <w:right w:val="single" w:sz="6" w:space="0" w:color="CBD5E1"/>
          <w:insideH w:val="single" w:sz="4" w:space="0" w:color="E2E8F0"/>
          <w:insideV w:val="single" w:sz="4" w:space="0" w:color="E2E8F0"/>
        </w:tblBorders>
        <w:tblCellMar>
          <w:top w:w="120" w:type="dxa"/>
          <w:left w:w="140" w:type="dxa"/>
          <w:bottom w:w="120" w:type="dxa"/>
          <w:right w:w="140" w:type="dxa"/>
        </w:tblCellMar>
      </w:tblPr>
      <w:tr>
        <w:tc><w:tcPr><w:tcW w:w="700" w:type="dxa"/><w:shd w:val="clear" w:color="auto" w:fill="EEF2FF"/></w:tcPr><w:p><w:pPr><w:spacing w:before="40" w:after="40"/></w:pPr><w:r><w:rPr><w:b/><w:sz w:val="19"/><w:color w:val="312E81"/></w:rPr><w:t>No</w:t></w:r></w:p></w:tc>
        <w:tc><w:tcPr><w:tcW w:w="2800" w:type="dxa"/><w:shd w:val="clear" w:color="auto" w:fill="EEF2FF"/></w:tcPr><w:p><w:pPr><w:spacing w:before="40" w:after="40"/></w:pPr><w:r><w:rPr><w:b/><w:sz w:val="19"/><w:color w:val="312E81"/></w:rPr><w:t>Judul Pelajaran Video</w:t></w:r></w:p></w:tc>
        <w:tc><w:tcPr><w:tcW w:w="1100" w:type="dxa"/><w:shd w:val="clear" w:color="auto" w:fill="EEF2FF"/></w:tcPr><w:p><w:pPr><w:spacing w:before="40" w:after="40"/></w:pPr><w:r><w:rPr><w:b/><w:sz w:val="19"/><w:color w:val="312E81"/></w:rPr><w:t>Durasi</w:t></w:r></w:p></w:tc>
        <w:tc><w:tcPr><w:tcW w:w="1400" w:type="dxa"/><w:shd w:val="clear" w:color="auto" w:fill="EEF2FF"/></w:tcPr><w:p><w:pPr><w:spacing w:before="40" w:after="40"/></w:pPr><w:r><w:rPr><w:b/><w:sz w:val="19"/><w:color w:val="312E81"/></w:rPr><w:t>ID YouTube</w:t></w:r></w:p></w:tc>
        <w:tc><w:tcPr><w:tcW w:w="3360" w:type="dxa"/><w:shd w:val="clear" w:color="auto" w:fill="EEF2FF"/></w:tcPr><w:p><w:pPr><w:spacing w:before="40" w:after="40"/></w:pPr><w:r><w:rPr><w:b/><w:sz w:val="19"/><w:color w:val="312E81"/></w:rPr><w:t>Ringkasan Pembelajaran</w:t></w:r></w:p></w:tc>
      </w:tr>';

    foreach ($lessons as $idx => $l) {
        $no = $idx + 1;
        $title = $l['title'];
        $duration = $l['duration'];
        $yt = $l['youtube'];
        $desc = $l['desc'];
        $bg = ($idx % 2 === 0) ? 'FFFFFF' : 'F8FAFC';

        $html .= "<w:tr>
          <w:tc><w:tcPr><w:tcW w:w=\"700\" w:type=\"dxa\"/><w:shd w:val=\"clear\" w:color=\"auto\" w:fill=\"$bg\"/></w:tcPr><w:p><w:pPr><w:spacing w:before=\"40\" w:after=\"40\"/></w:pPr><w:r><w:rPr><w:sz w:val=\"19\"/><w:color w:val=\"334155\"/></w:rPr><w:t>$no</w:t></w:r></w:p></w:tc>
          <w:tc><w:tcPr><w:tcW w:w=\"2800\" w:type=\"dxa\"/><w:shd w:val=\"clear\" w:color=\"auto\" w:fill=\"$bg\"/></w:tcPr><w:p><w:pPr><w:spacing w:before=\"40\" w:after=\"40\"/></w:pPr><w:r><w:rPr><w:b/><w:sz w:val=\"19\"/><w:color w:val=\"0F172A\"/></w:rPr><w:t>".escapeXml($title)."</w:t></w:r></w:p></w:tc>
          <w:tc><w:tcPr><w:tcW w:w=\"1100\" w:type=\"dxa\"/><w:shd w:val=\"clear\" w:color=\"auto\" w:fill=\"$bg\"/></w:tcPr><w:p><w:pPr><w:spacing w:before=\"40\" w:after=\"40\"/></w:pPr><w:r><w:rPr><w:sz w:val=\"19\"/><w:color w:val=\"059669\"/><w:rFonts w:ascii=\"Courier New\" w:hAnsi=\"Courier New\"/></w:rPr><w:t>".escapeXml($duration)."</w:t></w:r></w:p></w:tc>
          <w:tc><w:tcPr><w:tcW w:w=\"1400\" w:type=\"dxa\"/><w:shd w:val=\"clear\" w:color=\"auto\" w:fill=\"$bg\"/></w:tcPr><w:p><w:pPr><w:spacing w:before=\"40\" w:after=\"40\"/></w:pPr><w:r><w:rPr><w:sz w:val=\"19\"/><w:color w:val=\"4F46E5\"/><w:rFonts w:ascii=\"Courier New\" w:hAnsi=\"Courier New\"/></w:rPr><w:t>".escapeXml($yt)."</w:t></w:r></w:p></w:tc>
          <w:tc><w:tcPr><w:tcW w:w=\"3360\" w:type=\"dxa\"/><w:shd w:val=\"clear\" w:color=\"auto\" w:fill=\"$bg\"/></w:tcPr><w:p><w:pPr><w:spacing w:before=\"40\" w:after=\"40\"/></w:pPr><w:r><w:rPr><w:sz w:val=\"19\"/><w:color w:val=\"475569\"/></w:rPr><w:t>".escapeXml($desc).'</w:t></w:r></w:p></w:tc>
        </w:tr>';
    }

    $html .= '</w:tbl><w:p><w:pPr><w:spacing w:before="0" w:after="140"/></w:pPr></w:p>';

    return $html;
}

// BINA DOKUMEN UTAMA
$docBody = '';

// ==================== COVER PAGE RESMI ====================
$docBody .= '<w:p><w:pPr><w:spacing w:before="1000" w:after="140"/><w:jc w:val="center"/></w:pPr><w:r><w:rPr><w:rFonts w:ascii="Plus Jakarta Sans" w:hAnsi="Plus Jakarta Sans"/><w:b/><w:sz w:val="26"/><w:color w:val="059669"/></w:rPr><w:t>BUKU PANDUAN PENGGUNA RESMI (USER MANUAL)</w:t></w:r></w:p>';
$docBody .= '<w:p><w:pPr><w:spacing w:before="60" w:after="180"/><w:jc w:val="center"/></w:pPr><w:r><w:rPr><w:rFonts w:ascii="Plus Jakarta Sans" w:hAnsi="Plus Jakarta Sans"/><w:b/><w:sz w:val="56"/><w:color w:val="0F172A"/></w:rPr><w:t>SIAGA BUMI-SSK</w:t></w:r></w:p>';
$docBody .= '<w:p><w:pPr><w:spacing w:before="0" w:after="400"/><w:jc w:val="center"/></w:pPr><w:r><w:rPr><w:rFonts w:ascii="Plus Jakarta Sans" w:hAnsi="Plus Jakarta Sans"/><w:sz w:val="26"/><w:color w:val="475569"/></w:rPr><w:t>Integrasi Wawasan Lingkungan dan Dinamika Kependudukan dalam Kurikulum Sekolah Siaga Kependudukan</w:t></w:r></w:p>';

$docBody .= callout(
    'KONSORSIUM PENGEMBANG & LEMBAGA PEMRAKARSA SULAWESI UTARA',
    "Badan Kependudukan dan Keluarga Berencana Nasional (BKKBN) Perwakilan Provinsi Sulawesi Utara\n".
    "Universitas Negeri Manado (UNIMA) — Kurikulum, Pedagogi & Evaluasi Kependudukan\n".
    "Universitas Pembangunan Indonesia (UNPI) — Infrastruktur & Rekayasa Perangkat Lunak\n".
    "Institut Pemerintahan Dalam Negeri (IPDN) Kampus Sulawesi Utara — Ketahanan Sosial & Kepamongprajaan\n".
    "Sekolah Pilot: SMP Negeri 1 Bitung & Sekolah Rakyat Menengah Atas 44 Minahasa\n".
    'Domain Produksi: https://demo.gustiswandana.space | Tahun Rilis: 2026',
    'success'
);

$docBody .= '<w:p><w:pPr><w:spacing w:before="600" w:after="0"/><w:jc w:val="center"/></w:pPr><w:r><w:rPr><w:sz w:val="20"/><w:color w:val="94A3B8"/></w:rPr><w:t>Dokumen Resmi Manual Book Operasional LMS Berbasis Kurikulum Riil Aplikasi</w:t></w:r></w:p>';

$docBody .= pageBreak();

// ==================== DAFTAR ISI ====================
$docBody .= h1('Daftar Isi');
$docBody .= p('Buku panduan ini memuat pedoman operasional lengkap bagi seluruh pemangku kepentingan platform SIAGA BUMI-SSK:');
$docBody .= bullet('Bab 1: Pendahuluan & Gambaran Umum', 0, '1.', 'Visi kelembagaan BKKBN, konsorsium kampus, 3 pilar kurikulum, sekolah percontohan, dan matriks RBAC.');
$docBody .= bullet('Bab 2: Akses & Autentikasi Pengguna', 0, '2.', 'Registrasi mandiri siswa baru, login akun, eye toggle kata sandi, dan tabel kredensial resmi.');
$docBody .= bullet('Bab 3: Kurikulum & Silabus Materi Riil Aplikasi', 0, '3.', 'Bedah lengkap 3 kursus resmi di aplikasi (Mengenai BKKBN, Apa sih SSK?, PPKS), 6 video pelajaran YouTube, durasi, dan butir kuis checkpoint.');
$docBody .= bullet('Bab 4: Panduan Siswa Pembelajar (Siswa)', 0, '4.', 'Dasbor satu aksi utama, pemutar video YouTube bebas iklan, live tracking 90%, catatan waktu interaktif, kuis kelulusan 70%, dan sertifikat.');
$docBody .= bullet('Bab 5: Panduan Guru / Pendidik (Guru)', 0, '5.', 'Unified Course Builder, integrasi video YouTube, manajemen modul materi, bank soal kuis, kunci jawaban & pembahasan.');
$docBody .= bullet('Bab 6: Panduan Operator Sekolah (Operator)', 0, '6.', 'Dasbor sekolah, pendaftaran satuan & impor CSV masal, reset kata sandi, dan verifikasi kelulusan kelas.');
$docBody .= bullet('Bab 7: Panduan Admin Pusat (Admin Pusat)', 0, '7.', 'Dasbor provinsi BKKBN, master data sekolah, kurasi visibilitas mode galeri (is_visible_to_guest), dan tiket bantuan.');
$docBody .= bullet('Bab 8: Panduan Penyuluh & Tamu (Mode Galeri)', 0, '8.', 'Penyuluhan lapangan, peninjauan kurikulum tanpa distraksi, dan batasan hak akses view-only.');
$docBody .= bullet('Bab 9: Sistem Sertifikasi Digital & Verifikasi QR Code', 0, '9.', 'Tiga lapis pengamanan, nomor seri unik, pemindaian QR Code kamera ponsel, dan verifikasi publik.');
$docBody .= bullet('Bab 10: Tanya Jawab Umum & Solusi Kendala Teknis (FAQ)', 0, '10.', 'Solusi ambang tontonan 90%, pengulangan kuis remedial 70%, perbaikan ejaan nama, dan video YouTube.');

$docBody .= pageBreak();

// ==================== BAB 1 ====================
$docBody .= h1('Bab 1: Pendahuluan & Gambaran Umum');
$docBody .= p('SIAGA BUMI-SSK (Sekolah Siaga Kependudukan) adalah platform manajemen pembelajaran digital (Learning Management System / LMS) yang dirancang secara khusus untuk mengintegrasikan pendidikan wawasan kependudukan, pelestarian lingkungan hidup, serta pencegahan dan penanganan kekerasan seksual ke dalam ekosistem pendidikan sekolah menengah.');

$docBody .= h2('1.1 Konsorsium Lembaga Pemrakarsa');
$docBody .= p('Platform ini diinisiasi dengan komitmen bersama BKKBN Perwakilan Provinsi Sulawesi Utara bersama konsorsium perguruan tinggi dan lembaga kedinasan terkemuka:');
$docBody .= bullet('UNIMA (Universitas Negeri Manado):', 0, '1.', 'Penyusunan kurikulum pedagogis dan evaluasi pembelajaran kependudukan.');
$docBody .= bullet('UNPI (Universitas Pembangunan Indonesia):', 0, '2.', 'Pengembangan teknologi, infrastruktur server, dan arsitektur sistem informasi.');
$docBody .= bullet('IPDN Kampus Sulawesi Utara:', 0, '3.', 'Wawasan ketahanan sosial, kepamongprajaan, dan tata kelola masyarakat.');
$docBody .= bullet('BKKBN Perwakilan Sulawesi Utara:', 0, '4.', 'Regulasi program Bangga Kencana, penyuluhan Generasi Berencana (GenRe), dan koordinasi SSK.');

$docBody .= imageDrawing('rId2', 'Gambar 1.1: Tampilan Beranda Utama SIAGA BUMI-SSK dengan kurasi 3 pilar kurikulum resmi, panduan belajar terarah, serta logo konsorsium BKKBN, UNIMA, UNPI, dan IPDN.', 1);

$docBody .= h2('1.2 Tiga Pilar Kurikulum Strategis');
$docBody .= p('Materi pembelajaran dalam SIAGA BUMI-SSK distandarisasi ke dalam 3 fokus pembelajaran strategis:');
$docBody .= bullet('Materi Umum Kependudukan (GenRe & BKKBN - Aksen Indigo #4F46E5):', 0, '•', 'Pengenalan peran strategis BKKBN, bonus demografi, program Generasi Berencana (GenRe), 8 fungsi keluarga, dan pendewasaan usia perkawinan.');
$docBody .= bullet('Materi Umum Lingkungan (Siaga Bumi - Aksen Emerald #059669):', 0, '•', 'Edukasi krisis iklim global, pelestarian flora-fauna endemik Sulawesi Utara, aksi Siaga Bumi, bank sampah sekolah, dan konservasi sumber daya air Minahasa.');
$docBody .= bullet('Kekerasan Sexual (PPKS - Aksen Rose Terracotta #E11D48):', 0, '•', 'Pencegahan dan penanganan kekerasan seksual di lingkungan satuan pendidikan, pemahaman batasan tubuh pribadi remaja (consent), kanal aduan ramah anak, dan pendampingan sahabat sebaya.');

$docBody .= h2('1.3 Sekolah Mitra Percontohan (Pilot Schools)');
$docBody .= p('Sesuai arahan kelembagaan, seluruh implementasi materi, peserta didik, dan administrasi sekolah saat ini dipusatkan pada 2 satuan pendidikan percontohan di Sulawesi Utara:');
$docBody .= bullet('SMP Negeri 1 Bitung (NPSN: 40101511):', 0, '1.', 'Jl. Sam Ratulangi No. 12, Pakadoodan, Madidir, Kota Bitung, Sulawesi Utara.');
$docBody .= bullet('Sekolah Rakyat Menengah Atas 44 Minahasa (NPSN: 40104444):', 0, '2.', 'Jl. Raya Tanawangko No. 44, Tombariri, Kabupaten Minahasa, Sulawesi Utara.');

$docBody .= h2('1.4 Matriks Hak Akses Pengguna (RBAC 5 Peran)');
$docBody .= p('Platform menerapkan pengamanan hak akses berbasis peran (Role-Based Access Control) secara ketat:');
$docBody .= tableRbac();

$docBody .= pageBreak();

// ==================== BAB 2 ====================
$docBody .= h1('Bab 2: Akses & Autentikasi Pengguna');
$docBody .= p('SIAGA BUMI-SSK menyediakan alur masuk dan pendaftaran yang mudah, aman, dan telah dioptimalkan untuk perangkat telepon pintar (mobile-first).');

$docBody .= h2('2.1 Pendaftaran Mandiri Siswa Baru (/register)');
$docBody .= p('Siswa yang belum memiliki akun dari sekolah dapat melakukan registrasi mandiri melalui langkah-langkah berikut:');
$docBody .= bullet('Langkah 1:', 0, '1.', 'Buka peramban (browser) dan akses alamat: https://demo.gustiswandana.space/register atau klik tombol "Daftar Siswa" pada bilah navigasi atas.');
$docBody .= bullet('Langkah 2:', 0, '2.', 'Isi nama lengkap sesuai kartu pelajar rapor (nama ini akan otomatis tercetak pada sertifikat kelulusan).');
$docBody .= bullet('Langkah 3:', 0, '3.', 'Pilih asal sekolah mitra (SMP Negeri 1 Bitung atau SRMA 44 Minahasa) dan cantumkan kota/domisili tempat tinggal.');
$docBody .= bullet('Langkah 4:', 0, '4.', 'Masukkan alamat email aktif, nomor WhatsApp untuk notifikasi, dan buat kata sandi minimal 6 karakter.');
$docBody .= bullet('Langkah 5:', 0, '5.', 'Klik tombol "Daftar Akun Siswa". Akun akan langsung aktif dan mengarahkan Anda ke Dasbor Pembelajaran.');

$docBody .= imageDrawing('rId4', 'Gambar 2.1: Antarmuka formulir registrasi mandiri siswa baru lengkap dengan pemilihan sekolah percontohan resmi di Sulawesi Utara.', 3);

$docBody .= h2('2.2 Masuk ke Akun Pembelajaran (/login)');
$docBody .= p('Untuk masuk ke dalam platform:');
$docBody .= bullet('Langkah 1:', 0, '1.', 'Kunjungi laman https://demo.gustiswandana.space/login.');
$docBody .= bullet('Langkah 2:', 0, '2.', 'Masukkan alamat email dan kata sandi akun terdaftar.');
$docBody .= bullet('Langkah 3:', 0, '3.', 'Gunakan fitur eye toggle (ikon mata) di sisi kanan kotak isian untuk memeriksa kebenaran pengetikan kata sandi.');
$docBody .= bullet('Langkah 4:', 0, '4.', 'Klik tombol hijau "Masuk ke Akun". Jika terdapat ketidaksesuaian data, sistem menampilkan pesan santun: "Email atau kata sandi belum pas. Coba cek lagi, ya."');

$docBody .= imageDrawing('rId3', 'Gambar 2.2: Laman masuk akun pembelajaran dengan kontrol intip kata sandi (eye toggle), tombol sentuh lega 48px, dan lencana panduan 5 peran resmi.', 2);

$docBody .= h2('2.3 Daftar Kredensial Pengujian & Akun Resmi');
$docBody .= p('Berikut adalah daftar kredensial bawaan yang disiapkan untuk uji coba dan evaluasi sistem:');
$docBody .= tableCredentials();

$docBody .= pageBreak();

// ==================== BAB 3 ====================
$docBody .= h1('Bab 3: Kurikulum & Silabus Materi Riil Aplikasi');
$docBody .= p('Platform SIAGA BUMI-SSK saat ini mengoperasikan 3 kursus resmi dengan 6 pelajaran video HD YouTube dan bank soal evaluasi pemahaman yang terhubung langsung ke basis data produksi:');

$docBody .= tableCoursesMaster();

$docBody .= h2('3.1 Kursus 1: Mengenai BKKBN (Pilar Kependudukan)');
$docBody .= callout(
    'METADATA KURSUS RESMI: MENGENAI BKKBN',
    "Kategori: Materi Umum Kependudukan (GenRe & BKKBN)\n".
    "Sekolah Pengampu: SMP Negeri 1 Bitung\n".
    "Instruktur Pengampu: Drs. Ronald Lumempouw\n".
    "Tingkat: Pemula (SMP/SMA) | Slug: mengenai-bkkbn-peran-kependudukan\n".
    "Akses Mode Galeri / Tamu: Ya (is_visible_to_guest = true)\n".
    'Deskripsi: Mengenal sejarah kelembagaan, visi transformasi, dan fungsi strategis BKKBN dalam memimpin program Bangga Kencana serta percepatan penurunan stunting menuju Indonesia Emas 2045.',
    'info'
);

$bkkbnLessons = [
    [
        'title' => '1.1: Makna & Filosofi Kelembagaan BKKBN',
        'duration' => '08:00',
        'youtube' => 'UCGVFxAa7nA',
        'desc' => 'Memahami transformasi, visi kelembagaan, serta makna logo dan komitmen baru BKKBN dalam melayani keluarga Indonesia yang berkualitas.',
    ],
    [
        'title' => '1.2: Program Bangga Kencana & Percepatan Penurunan Stunting',
        'duration' => '12:00',
        'youtube' => 'EA3wO1h8EX0',
        'desc' => 'Edukasi strategis mengenai pilar Pembangunan Keluarga, Kependudukan, dan Keluarga Berencana (Bangga Kencana) serta aksi percepatan penurunan stunting.',
    ],
];
$docBody .= tableLessonsDetails($bkkbnLessons);

$docBody .= callout(
    'SOAL KUIS EVALUASI PEMAHAMAN: MATERI 1.1 MAKNA & FILOSOFI BKKBN',
    "Pertanyaan: Apa kepanjangan resmi dan mandat utama dari BKKBN?\n\n".
    "• Pilihan A: Badan Kependudukan dan Keluarga Berencana Nasional  [KUNCI JAWABAN BENAR]\n".
    "• Pilihan B: Badan Koordinasi Kesejahteraan Bersama Nasional\n".
    "• Pilihan C: Badan Ketahanan Pangan dan Kependudukan Nasional\n".
    "• Pilihan D: Balai Konseling dan Keluarga Berencana Nasional\n\n".
    'Pembahasan Pedagogis: BKKBN adalah Badan Kependudukan dan Keluarga Berencana Nasional yang bertugas melaksanakan pengendalian penduduk dan penyelenggaraan keluarga berencana.',
    'success'
);

$docBody .= h2('3.2 Kursus 2: Apa sih SSK? (Pilar Lingkungan)');
$docBody .= callout(
    'METADATA KURSUS RESMI: APA SIH SSK?',
    "Kategori: Materi Umum Lingkungan (Siaga Bumi)\n".
    "Sekolah Pengampu: Sekolah Rakyat Menengah Atas 44 Minahasa\n".
    "Instruktur Pengampu: Ibu Martha Runtuwene, S.Pd\n".
    "Tingkat: Pemula (SMP/SMA) | Slug: apa-sih-ssk-sekolah-siaga-kependudukan\n".
    "Akses Mode Galeri / Tamu: Tidak (Tertutup khusus siswa & guru sekolah mitra)\n".
    'Deskripsi: Memahami filosofi Sekolah Siaga Kependudukan (SSK), integrasi materi wawasan kependudukan dan kepedulian lingkungan Siaga Bumi ke dalam mata pelajaran sekolah, serta pembentukan Pojok Kependudukan.',
    'info'
);

$sskLessons = [
    [
        'title' => '1.1: Apa Sih Sekolah Siaga Kependudukan (SSK)?',
        'duration' => '09:00',
        'youtube' => '6bGlT6CVohg',
        'desc' => 'Menjelaskan konsep dasar Sekolah Siaga Kependudukan (SSK), tujuan integrasi materi kependudukan ke dalam mata pelajaran sekolah, serta urgensi kepedulian remaja.',
    ],
    [
        'title' => '1.2: Panduan Pengenalan & Pojok Kependudukan SSK',
        'duration' => '11:00',
        'youtube' => 'x8PgjAKr-h8',
        'desc' => 'Tata cara pembentukan Pojok Kependudukan (Population Corner) di sekolah sebagai sentra literasi, sarana konseling sebaya, dan media integrasi edukasi lingkungan Siaga Bumi.',
    ],
];
$docBody .= tableLessonsDetails($sskLessons);

$docBody .= callout(
    'SOAL KUIS EVALUASI PEMAHAMAN: MATERI 1.1 KONSEP DASAR SSK',
    "Pertanyaan: Apa fungsi utama dari Pojok Kependudukan (Population Corner) di Sekolah Siaga Kependudukan?\n\n".
    "• Pilihan A: Sebagai ruang pertemuan staf guru saja\n".
    "• Pilihan B: Sebagai pusat informasi, literasi, perpustakaan mini, dan diskusi sebaya tentang kependudukan  [KUNCI JAWABAN BENAR]\n".
    "• Pilihan C: Sebagai tempat penyimpanan alat olahraga sekolah\n".
    "• Pilihan D: Sebagai ruang bimbingan konseling khusus pelanggaran disiplin\n\n".
    'Pembahasan Pedagogis: Pojok Kependudukan adalah sarana edukasi dan diskusi interaktif bagi siswa mengenai dinamika kependudukan dan isu lingkungan sekitar.',
    'success'
);

$docBody .= h2('3.3 Kursus 3: Pencegahan & Penanganan Kekerasan Seksual (PPKS)');
$docBody .= callout(
    'METADATA KURSUS RESMI: PENCEGAHAN & PENANGANAN KEKERASAN SEKSUAL (PPKS)',
    "Kategori: Kekerasan Sexual (Perlindungan Remaja & Ramah Anak)\n".
    "Sekolah Pengampu: SMP Negeri 1 Bitung\n".
    "Instruktur Pengampu: Dra. Maria Sondakh, M.Si\n".
    "Tingkat: Pemula (SMP/SMA) | Slug: pencegahan-dan-penanganan-kekerasan-seksual-ppks\n".
    "Akses Mode Galeri / Tamu: Ya (is_visible_to_guest = true)\n".
    'Deskripsi: Pedoman komprehensif bagi pelajar dan pendidik dalam mengidentifikasi, mencegah, serta menangani kekerasan seksual di satuan pendidikan dengan pendekatan ramah anak dan berkeadilan.',
    'info'
);

$ppksLessons = [
    [
        'title' => '1.1: Memahami Batasan & Pencegahan Kekerasan Seksual di Sekolah',
        'duration' => '09:00',
        'youtube' => 'EA3wO1h8EX0',
        'desc' => 'Mengenali bentuk-bentuk kekerasan seksual fisik dan verbal, membangun kesadaran batasan pribadi remaja (consent), dan menciptakan ruang aman di lingkungan belajar.',
    ],
    [
        'title' => '1.2: Peran Satgas PPKS & Mekanisme Pengaduan Ramah Anak',
        'duration' => '10:00',
        'youtube' => 'UCGVFxAa7nA',
        'desc' => 'Alur pelaporan yang aman dan terpercaya, perlindungan identitas korban, serta peran konseling sebaya dan guru BK dalam mendampingi peserta didik.',
    ],
];
$docBody .= tableLessonsDetails($ppksLessons);

$docBody .= callout(
    'SOAL KUIS EVALUASI PEMAHAMAN: MATERI 1.1 PENCEGAHAN PPKS DI SEKOLAH',
    "Pertanyaan: Apa langkah pertama yang tepat dilakukan apabila melihat atau mengalami tindakan kekerasan seksual di sekolah?\n\n".
    "• Pilihan A: Menyimpan rahasia dan merasa bersalah sendiri\n".
    "• Pilihan B: Segera melapor ke Satgas PPKS, guru BK, atau orang dewasa terpercaya yang aman  [KUNCI JAWABAN BENAR]\n".
    "• Pilihan C: Membalas dengan kekerasan fisik serupa\n".
    "• Pilihan D: Membagikan kejadian tersebut ke media sosial tanpa perlindungan privasi\n\n".
    'Pembahasan Pedagogis: Melaporkan kepada Satgas PPKS atau guru BK menjamin perlindungan privasi, pendampingan psikologis, dan penanganan kasus secara adil dan aman.',
    'success'
);

$docBody .= pageBreak();

// ==================== BAB 4 ====================
$docBody .= h1('Bab 4: Panduan Siswa Pembelajar (Siswa)');
$docBody .= p('Antarmuka siswa dirancang dengan pendekatan humanis, bebas dari elemen visual yang membingungkan, dan berfokus pada pengalaman belajar yang menyenangkan.');

$docBody .= h2('4.1 Dasbor Pembelajaran & Satu Aksi Utama');
$docBody .= p('Saat siswa membuka dasbor (/dashboard), perhatian langsung diarahkan pada tindakan belajar yang paling penting:');
$docBody .= bullet('Kartu Lanjutkan Belajar:', 0, '•', 'Menampilkan materi terakhir yang sedang dipelajari (misal: "Mengenai BKKBN" atau "Apa sih SSK?") lengkap dengan persentase kelulusan.');
$docBody .= bullet('Satu Aksi Utama Prominen:', 0, '•', 'Tombol hijau "Lanjutkan Video Terakhir" memungkinkan siswa melompat langsung ke materi aktif dengan 1 kali klik.');
$docBody .= bullet('Penyampai Progres Ramah:', 0, '•', 'Indikator linier menyampaikan sisa pencapaian secara memotivasi, contohnya: "Tinggal 2 materi lagi untuk dapat sertifikat kelulusan!".');

$docBody .= imageDrawing('rId5', 'Gambar 4.1: Dasbor Pembelajaran Siswa dengan kartu aksi primer "Lanjutkan Video Terakhir", indikator kelulusan bersahabat, dan filter topik materi.', 4);

$docBody .= h2('4.2 Teater Belajar & Pemutar Video HD (Classroom)');
$docBody .= p('Saat siswa membuka modul pembelajaran (/belajar/{slug}), ruang belajar digital menyajikan suasana teater yang tenang:');
$docBody .= bullet('Pemutar YouTube Bebas Iklan:', 0, '•', 'Video disematkan secara terisolasi tanpa rekomendasi video luar yang mengganggu konsentrasi.');
$docBody .= bullet('Live Watch Tracker (Ambang 90%):', 0, '•', 'Sistem secara otomatis menghitung durasi tontonan setiap 10 detik. Sebagai contoh, pada video Pelajaran 1.1 "Makna & Filosofi Kelembagaan BKKBN" berdurasi 08:00 (480 detik), siswa wajib menonton minimal 07:12 (432 detik) sebelum status pelajaran tuntas dan tombol kuis terbuka.');
$docBody .= bullet('Panel Navigasi 3 Tab:', 0, '•', 'Di sisi samping pemutar video terdapat tab Materi (silabus bab), Catatan (catatan berwaktu), dan Diskusi (tanya jawab).');

$docBody .= imageDrawing('rId6', 'Gambar 4.2: Ruang Belajar Digital (Classroom) dengan teater video YouTube HD bebas iklan, pelacak otomatis durasi tontonan 90%, dan tab navigasi interaktif.', 5);

$docBody .= h2('4.3 Catatan Waktu Interaktif (Timestamp Notes)');
$docBody .= p('Siswa dapat merekam poin penting penjelasan materi yang terhubung langsung ke detik video:');
$docBody .= bullet('Pengambilan Otomatis:', 0, '1.', 'Klik tab Catatan di samping video, lalu klik tombol "+ Ambil Menit Saat Ini". Sistem seketika mendeteksi stempel waktu berjalan (misal 04:12).');
$docBody .= bullet('Penyimpanan Cepat:', 0, '2.', 'Tulis catatan penting Anda lalu tekan pintasan Ctrl + Enter atau klik tombol Simpan Catatan.');
$docBody .= bullet('Pemutaran Ulang Instan:', 0, '3.', 'Catatan tersimpan dengan tombol interaktif "▶ 04:12 (Putar)". Kapan saja tombol tersebut diklik, video akan otomatis melompat dan memutar bagian materi pada detik tersebut.');

$docBody .= imageDrawing('rId7', 'Gambar 4.3: Panel Catatan Waktu Interaktif dengan tombol ambil menit video otomatis, pintasan simpan cepat, dan tag putar ulang (seekTo).', 6);

$docBody .= h2('4.4 Kuis Checkpoint & Toleransi Kelulusan 70%');
$docBody .= p('Setiap pelajaran dilengkapi dengan kuis evaluasi pemahaman mandiri:');
$docBody .= bullet('Tombol Sentuh Ergonomis:', 0, '•', 'Kartu pilihan jawaban A, B, C, D dirancang tinggi (min-h-14) agar mudah ditekan melalui layar sentuh smartphone.');
$docBody .= bullet('Batas Nilai Kelulusan:', 0, '•', 'Passing grade minimal adalah 70%. Siswa yang memperoleh nilai 70 ke atas dinyatakan lulus.');
$docBody .= bullet('Umpan Balik Ramah & Remedial Instan:', 0, '•', 'Jika perolehan nilai di bawah 70%, sistem tidak menghukum siswa melainkan menyajikan motivasi: "Sedikit lagi lulus passing grade 70%! Kamu bisa tonton ulang materi dan coba kuis lagi tanpa batasan." Siswa dapat mengklik tombol "Ulangi Kuis" untuk mencoba kembali.');

$docBody .= imageDrawing('rId8', 'Gambar 4.4: Tampilan kuis checkpoint dengan kartu opsi sentuh luas, umpan balik ramah jika nilai < 70%, dan tombol instan ulangi kuis.', 7);

$docBody .= pageBreak();

// ==================== BAB 5 ====================
$docBody .= h1('Bab 5: Panduan Guru / Pendidik (Guru)');
$docBody .= p('Akun Guru (seperti Ibu Martha Runtuwene, S.Pd di SRMA 44 Minahasa dan Drs. Ronald Lumempouw di SMPN 1 Bitung) difasilitasi dengan alat penyusunan kurikulum yang modern, memudahkan pengunggahan materi berbasis video YouTube, serta pembuatan bank soal evaluasi.');

$docBody .= h2('5.1 Alur Kerja Unified Course Builder');
$docBody .= p('Untuk menerbitkan materi kursus baru:');
$docBody .= bullet('Tahap 1: Pengisian Informasi Kursus:', 0, '1.', 'Tentukan judul kursus, pilih salah satu kategori pilar resmi (Kependudukan, Lingkungan, atau PPKS), pilih tingkat sekolah (SMP/SMA), cantumkan nama lengkap guru, dan pasang gambar cover 16:9.');
$docBody .= bullet('Tahap 2: Penyusunan Bab & Ingest Video YouTube:', 0, '2.', 'Buat nama modul bab, tambahkan pelajaran, dan tempelkan tautan URL YouTube (seperti https://www.youtube.com/watch?v=6bGlT6CVohg). Sistem secara otomatis mengekstrak durasi detik dan thumbnail video berkualitas tinggi.');
$docBody .= bullet('Tahap 3: Pembuatan Kuis & Pembahasan:', 0, '3.', 'Masukkan butir-butir pertanyaan pilihan ganda, tandai kunci jawaban yang benar, dan sertakan uraian penjelasan/pembahasan soal.');
$docBody .= bullet('Tahap 4: Publikasi Kursus:', 0, '4.', 'Klik tombol "Terbitkan Kursus" agar materi dapat langsung diakses oleh siswa di katalog.');

$docBody .= imageDrawing('rId11', 'Gambar 5.1: Antarmuka Manajer Kursus untuk menyusun materi ajar, mengatur silabus bab, dan mengontrol visibilitas publik/tamu.', 10);

$docBody .= pageBreak();

// ==================== BAB 6 ====================
$docBody .= h1('Bab 6: Panduan Operator Sekolah (Operator)');
$docBody .= p('Operator Sekolah (seperti Meivy Karundeng, S.Kom di SMP Negeri 1 Bitung) bertanggung jawab atas administrasi peserta didik pada masing-masing satuan pendidikan.');

$docBody .= h2('6.1 Pendaftaran Siswa Manual & Impor Masal CSV');
$docBody .= p('Operator dapat mendaftarkan siswa baru ke sistem melalui dua cara:');
$docBody .= bullet('Pendaftaran Satuan:', 0, '1.', 'Buka menu Siswa (/admin/students), klik "+ Tambah Siswa", lalu isi nama, NISN, kelas, email, nomor HP, dan kata sandi.');
$docBody .= bullet('Impor Masal CSV:', 0, '2.', 'Klik "Impor Masal via CSV", unduh template CSV resmi, isi daftar nama siswa rombongan belajar di Excel/Spreadsheet, lalu unggah kembali berkas .csv tersebut.');

$docBody .= h2('6.2 Pengaturan Ulang (Reset) Kata Sandi Akun Siswa');
$docBody .= p('Jika ada siswa yang lupa kata sandi akunnya:');
$docBody .= bullet('Langkah:', 0, '•', 'Cari nama siswa pada tabel data siswa, klik tombol "Reset Sandi". Kata sandi akun siswa akan otomatis kembali ke sandi standar: password.');

$docBody .= imageDrawing('rId12', 'Gambar 6.1: Panel Manajemen Siswa Sekolah untuk penambahan akun satuan, impor masal CSV, dan pengaturan ulang (reset) kata sandi.', 11);

$docBody .= pageBreak();

// ==================== BAB 7 ====================
$docBody .= h1('Bab 7: Panduan Admin Pusat (Admin Pusat)');
$docBody .= p('Admin Pusat (seperti Dr. Hendra Sompotan, M.Si) memiliki kendali tertinggi atas tata kelola ekosistem SIAGA BUMI-SSK se-Provinsi Sulawesi Utara.');

$docBody .= h2('7.1 Dasbor Analitik Tingkat Provinsi');
$docBody .= p('Dasbor menyajikan data makro perkembangan sekolah siaga kependudukan: total siswa aktif lintas kabupaten/kota, statistik kelulusan sertifikat, serta peminatan 3 pilar materi.');

$docBody .= imageDrawing('rId10', 'Gambar 7.1: Dasbor Analitik Administrator Pusat BKKBN Perwakilan Sulawesi Utara untuk pemantauan data makro sekolah dan kurikulum provinsi.', 9);

$docBody .= h2('7.2 Fitur Kurasi Mode Galeri Tamu (is_visible_to_guest)');
$docBody .= p('Admin Pusat bertindak sebagai kurator konten untuk publik dan instansi luar. Pada menu Manajer Kursus (/admin/courses), terdapat sakelar cepat "Akses Tamu / Galeri":');
$docBody .= bullet('Kursus Mengenai BKKBN & PPKS (Aktif):', 0, '•', 'Disetel tampil di galeri publik sehingga penyuluh lapangan dan tamu dapat menyimak materi.');
$docBody .= bullet('Kursus Apa sih SSK? (Terkunci):', 0, '•', 'Disetel tertutup untuk kurikulum internal sekolah binaan SRMA 44 Minahasa.');

$docBody .= pageBreak();

// ==================== BAB 8 ====================
$docBody .= h1('Bab 8: Panduan Penyuluh & Tamu (Mode Galeri)');
$docBody .= p('Peran Penyuluh / Tamu difungsikan khusus bagi Petugas Lapangan Keluarga Berencana (PLKB) BKKBN Sulawesi Utara, peninjau Dinas Pendidikan, serta mitra kampus.');

$docBody .= h2('8.1 Karakteristik Mode Galeri (View-Only)');
$docBody .= p('Saat masuk menggunakan akun tamu (guest@edutube.org):');
$docBody .= bullet('Materi Terkurasi:', 0, '•', 'Hanya kursus yang telah disetujui untuk akses tamu oleh Admin Pusat yang akan tampil di katalog.');
$docBody .= bullet('Sarana Penyuluhan Lapangan:', 0, '•', 'Penyuluh dapat memutar video edukasi resmi di hadapan kelompok Pusat Informasi dan Konseling Remaja (PIK-R) di desa/kelurahan.');
$docBody .= bullet('Integritas Akademik:', 0, '•', 'Akun tamu tidak dapat menyimpan catatan pribadi, mengirim komentar, mengerjakan kuis, atau memicu penerbitan sertifikat siswa.');

$docBody .= pageBreak();

// ==================== BAB 9 ====================
$docBody .= h1('Bab 9: Sistem Sertifikasi Digital & Verifikasi QR Code');
$docBody .= p('Setiap e-sertifikat yang diterbitkan oleh platform SIAGA BUMI-SSK dilindungi oleh sistem otentikasi digital berlapis.');

$docBody .= h2('9.1 Tiga Syarat Mutlak Penerbitan Sertifikat');
$docBody .= p('Sertifikat kelulusan hanya akan diterbitkan oleh sistem jika memenuhi syarat:');
$docBody .= bullet('Syarat 1:', 0, '1.', 'Siswa telah menonton dan menuntaskan 100% video pelajaran dalam kursus terkait (progres tontonan penuh).');
$docBody .= bullet('Syarat 2:', 0, '2.', 'Siswa telah lulus seluruh kuis checkpoint dengan nilai minimal 70% di setiap materi.');

$docBody .= h2('9.2 Laman Verifikasi Publik Ber-QR Code (/sertifikat/{kode})');
$docBody .= p('Setiap lembar sertifikat memiliki QR Code dinamis dan nomor seri unik terstandarisasi (contoh riil di database: CERT-SSK-BUDI-5BAC414B untuk siswa Budi Pratama dan CERT-SSK-SARAH-A1350F47 untuk siswi Sarah Connor):');
$docBody .= bullet('Pemeriksaan Tanpa Login:', 0, '•', 'Pihak universitas, sekolah lanjutan, maupun orang tua dapat memindai QR Code menggunakan kamera ponsel mana saja tanpa perlu membuat akun.');
$docBody .= bullet('Data Keaslian Resmi:', 0, '•', 'Laman publik menyajikan status lencana hijau "Terverifikasi Asli & Sah", nama lengkap siswa, nama sekolah mitra, nilai evaluasi, dan tanggal resmi kelulusan dari basis data server.');

$docBody .= imageDrawing('rId9', 'Gambar 9.1: Laman Verifikasi Publik Resmi ber-QR Code yang dapat diakses oleh publik dan perguruan tinggi tanpa memerlukan otentikasi login.', 8);

$docBody .= pageBreak();

// ==================== BAB 10 ====================
$docBody .= h1('Bab 10: Tanya Jawab Umum & Solusi Kendala Teknis (FAQ)');
$docBody .= p('Berikut adalah rangkuman tanya jawab yang sering dihadapi pengguna di lapangan:');

$docBody .= h2('10.1 Mengapa Tombol Kuis Checkpoint Belum Bisa Dibuka?');
$docBody .= p('Solusi: Tombol kuis baru akan terbuka secara otomatis setelah Anda menonton minimal 90% durasi video materi. Pastikan video diputar hingga tuntas dan koneksi internet stabil saat memutar video.');

$docBody .= h2('10.2 Apa yang Harus Dilakukan Jika Nilai Kuis di Bawah 70%?');
$docBody .= p('Solusi: Sistem SIAGA BUMI-SSK tidak membatasi kesempatan mengulang. Klik tombol "Ulangi Kuis", pelajari kembali pembahasan soal yang ditampilkan di bawah pertanyaan, dan kirimkan kembali jawaban Anda hingga lulus.');

$docBody .= h2('10.3 Mengapa Video YouTube Menampilkan Tulisan "Video Unavailable"?');
$docBody .= p('Solusi: Hal ini biasanya terjadi jika pemilik video di YouTube mengubah visibilitas video menjadi Privat atau mematikan izin penyematan (embedding). Laporkan hal ini kepada Guru atau Admin Pusat melalui tab diskusi materi agar URL video alternatif dapat segera dipasang.');

$docBody .= h2('10.4 Bagaimana Cara Membetulkan Salah Eja Nama pada Lembar Sertifikat?');
$docBody .= p('Solusi: Nama pada sertifikat dicetak mengacu pada data master siswa. Hubungi Operator Sekolah untuk membetulkan ejaan nama pada profil akun Anda, setelah itu sertifikat otomatis menampilkan nama yang benar.');

$docBody .= h2('10.5 Apakah Aplikasi Dapat Diakses di Smartphone?');
$docBody .= p('Solusi: Ya, seluruh tampilan SIAGA BUMI-SSK telah dirancang dengan prinsip Mobile-First, responsif, dan tombol-tombol sentuh lega (min-h-11 hingga min-h-14) sehingga nyaman digunakan pada perangkat Android maupun iPhone.');

$docBody .= '<w:p><w:pPr><w:spacing w:before="600" w:after="100"/><w:jc w:val="center"/></w:pPr><w:r><w:rPr><w:rFonts w:ascii="Plus Jakarta Sans" w:hAnsi="Plus Jakarta Sans"/><w:i/><w:color w:val="94A3B8"/><w:sz w:val="20"/></w:rPr><w:t>— Akhir Dokumen Buku Panduan Pengguna Resmi SIAGA BUMI-SSK © 2026 —</w:t></w:r></w:p>';

// SECTPR (Page Margins: Standard 1 inch / 1440 twips, A4 Paper Width 11906, Height 16838)
$docBody .= '<w:sectPr>
  <w:pgSz w:w="11906" w:h="16838"/>
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

// Masukkan 11 gambar screenshot ke folder word/media/
$zip->addFile($images['img1'], 'word/media/image1.png');
$zip->addFile($images['img2'], 'word/media/image2.png');
$zip->addFile($images['img3'], 'word/media/image3.png');
$zip->addFile($images['img4'], 'word/media/image4.png');
$zip->addFile($images['img5'], 'word/media/image5.png');
$zip->addFile($images['img6'], 'word/media/image6.png');
$zip->addFile($images['img7'], 'word/media/image7.png');
$zip->addFile($images['img8'], 'word/media/image8.png');
$zip->addFile($images['img9'], 'word/media/image9.png');
$zip->addFile($images['img10'], 'word/media/image10.png');
$zip->addFile($images['img11'], 'word/media/image11.png');

$zip->close();

// Salin ke direktori publik dan artefak
copy($outputDocx, $publicDocx);
copy($outputDocx, $artifactDocx);

echo "Berhasil membuat berkas DOCX!\n";
echo "1. Lokasi Root: $outputDocx (".round(filesize($outputDocx) / 1024, 2)." KB)\n";
echo "2. Lokasi Publik: $publicDocx\n";
echo "3. Lokasi Artefak: $artifactDocx\n";
