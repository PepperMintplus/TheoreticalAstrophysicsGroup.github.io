<?php

#from https://stackoverflow.com/a/46872528/1550243
function encrypt($plaintext, $password) {
    $method = "AES-256-CBC";
    $key = hash('sha256', $password, true);
    $iv = openssl_random_pseudo_bytes(16);

    $ciphertext = openssl_encrypt($plaintext, $method, $key, OPENSSL_RAW_DATA, $iv);
    $hash = hash_hmac('sha256', $ciphertext . $iv, $key, true);

    return $iv . $hash . $ciphertext;
}

function escape_all_latex_special_chars($string) {

    # Escape some special latex characters
    $string = str_replace("_", "\\_{}", $string);
    $string = str_replace("#", "\\#{}", $string);
    $string = str_replace("%", "\\%{}", $string);
    $string = str_replace("$", "\\\${}", $string);
    $string = str_replace("&", "\\&{}", $string);
    $string = str_replace("{", "\\{", $string);
    $string = str_replace("}", "\\}", $string);
    $string = str_replace("^", "\\textasciicircum{}", $string);
    $string = str_replace("~", "\\textasciitilde{}", $string);
    $string = str_replace("\\", "\\textbackslash{}", $string);

    return $string;

}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['recaptcha_response'])) {

    # Build POST request:
    $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
    $recaptcha_secret = 
    $recaptcha_response = $_POST['recaptcha_response'];

    # Make and decode POST request:
    $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
    $recaptcha = json_decode($recaptcha);

    # Take action based on the score returned:
    if ($recaptcha->score >= 0.0) {

    # Verified

    # Form variables
    $iry = $_POST['InputRankYear'];
    $iln = $_POST['InputLastName'];
    $ifn = $_POST['InputFirstName'];
    $ilnr = $_POST['InputLastNameRomaji'];
    $ifnr = $_POST['InputFirstNameRomaji'];
    $ie1 = $_POST['InputEmail1'];
    $ie2 = $_POST['InputEmail2'];
    $it1 = $_POST['InputTel1'];
    $it2 = $_POST['InputTel2'];
    $it3 = $_POST['InputTel3'];
    $it4 = $_POST['InputTel4'];
    $ip = $_POST['InputPostal'];
    $ia = $_POST['InputAddress'];
    $ir = $_POST['InputResearch'];
    $ih = $_POST['InputHomepage'];

    # Translating inputted ranks to the rank ids used in the webpage
    $rank_ids = array(
      '教授' => 'professor', 
      'Professor' => 'professor', 
      '准教授' => 'associate_professor', 
      'Associate Professor' => 'associate_professor', 
      '講師' => 'lecturer', 
      'Lecturer' => 'lecturer', 
      '助教' => 'assistant_professor', 
      'Assistant Professor' => 'assistant_professor', 
      '研究員' => 'postdoc', 
      'Postdoc' => 'postdoc', 
      '博士課程研究生' => 'postdoc_student', 
      'Postdoctoral Student' => 'postdoc_student', 
      'D5' => 'd5', 
      'D4' => 'd4', 
      'D3' => 'd3', 
      'D2' => 'd2', 
      'D1' => 'd1', 
      '研究生' => 'research_student', 
      'Research Student' => 'research_student', 
      'M3' => 'm3', 
      'M2' => 'm2', 
      'M1' => 'm1', 
      'Y4' => 'y4', 
      '事務補助' => 'admin_assistant', 
      'Administrative Assistant' => 'admin_assistant', 
    );

    # Email username
    $email = explode('@', $ie1);
    $uname = $email[0];

    # Create filenames
    $fbase = '../../membersform_data/';  // For site on charon
    // $fbase = '../../../membersform_data/';  // For my LAMPP setup
    $fname_tex = $fbase . strtolower($ilnr) . "_" . strtolower($ifnr) . '.tex';
    $fname_yml = $fbase . strtolower($ilnr) . "_" . strtolower($ifnr) . '.html';

    # No forward slashes if strings empty
    $fse2 = empty($ie2) ? "" : "/";
    $fst4 = empty($it4) ? "" : "/";

    # Create position string for students
    $pos = preg_match('/^[DMY][0-5]/', $iry) ? $iry : "";
    $pos = preg_match('/研究生/u', $iry) ? "研究生" : $pos;

    # Replace underscores in emails (assume this is the only special character in email addresses)
    $ie1 = str_replace("_", "\\_", $ie1);
    $ie2 = str_replace("_", "\\_", $ie2);

    # TODO: Test whether replacements are working
    # Replace latex characters that need escaping in address
    $ia = escape_all_latex_special_chars($ia);

    # TODO: make separate English and Japanese yamllines and files. 

    # Construct latex lines and yaml lines
    $latexlines = "$iln $ifn \\small{ $pos } & 〒$ip $ia & $it1 $fst4 $it4 \\\\\n$ilnr $ifnr & \\texttt{ $ie1 } $fse2 \\texttt{ $ie2 } & $it2";
    $yamllines = "---\nname: $iln $ifn\nname: $ifnr $ilnr\nemail: $uname\ntel: $it3\nposition: $rank_ids[$iry]\nhomepage: \"$ih\"\nresearch: $ir\n---";

    # Fix spaces in curly braces
    $latexlines = str_replace("{ ", "{", $latexlines);
    $latexlines = str_replace(" }", "}", $latexlines);

    # Fix zenkaku hyphen to hankaku hyphen
    $latexlines = str_replace("‐", "-", $latexlines);
    $yamllines = str_replace("‐", "-", $yamllines);

    # Replace all zenkaku spaces and repeating spaces with one hankaku space
    # Unicode u is crucial here, otherwise character set is changed.
    $latexlines = preg_replace('/[ 　]+/u', ' ', $latexlines);
    $yamllines = preg_replace('/[ 　]+/u', ' ', $yamllines);

    # Fix html http -> https
    $yamllines = str_replace("http:", "https:", $yamllines);

    # Unify commas
    $latexlines = str_replace("、", "，", $latexlines);
    $yamllines = str_replace("、", "，", $yamllines);

    # Email 
    $formcontent = "$latexlines\n\n$yamllines";
    $recipient = 'astro.ccs.tsukuba@gmail.com';
    $subject = "TAG member $iln $ifn ($ilnr $ifnr) info";
    $mailheader = "From: $ie1 \n";
    mail($recipient, $subject, $formcontent, $mailheader);

    # Encrypt the private data with OpenSSL
    $ssl_password = 
    $latexlines_encrypted = encrypt($latexlines, $ssl_password);

    # Write files
    $fcon = fopen($fname_tex . ".enc", 'w');
    fwrite($fcon, $latexlines_encrypted);
    fclose($fcon);
    $fcon = fopen($fname_yml, 'w');
    fwrite($fcon, $yamllines);
    fclose($fcon);

    echo "<p>　</p>";
    echo "<p>　</p>";
    echo '<h4 class="pull-left">ご協力ありがとうございました。　</h4>';
    echo '<a href="."><button type="submit" class="btn btn-primary pull-right">再入力</button></a>';

    } else {

    # Not verified

    echo "<p>　</p>";
    echo "<p>　</p>";
    echo '<h4 class="pull-left">You have failed the recaptcha test. Please try again.　</h4>';
    echo '<a href="."><button class="btn btn-primary pull-right">再入力</button></a>';

    }
} ?>
