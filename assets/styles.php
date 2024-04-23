<?php
  header("Content-type: text/css; charset=utf-8");
  $host="localhost";
  $user="root";
  $pass="";
  $dbname="quiz"; 

  $conn = mysqli_connect($host, $user, $pass, $dbname);

  $query = "SELECT * FROM quiz_design qd
            LEFT JOIN wrapper w ON qd.wrapperID = w.wrapperID
            LEFT JOIN product_container pc ON qd.pconID = pc.pconID
            LEFT JOIN p p ON qd.pID = p.pID
            LEFT JOIN progress_bar pb ON qd.progressBarID = pb.progressBarID
            LEFT JOIN progress pr ON qd.progressID = pr.progressID
            LEFT JOIN question_container qc ON qd.qconID = qc.qconID
            LEFT JOIN result_subheading rs ON qd.rSubheadingID = rs.rSubheadingID
            LEFT JOIN result_title rt ON qd.rTitleID = rt.rTitleID
            LEFT JOIN spacer s ON qd.spacerID = s.spacerID
            LEFT JOIN voucher_h1 vh1 ON qd.vh1ID = vh1.vh1ID
            LEFT JOIN voucher_h4 vh4 ON qd.vh4ID = vh4.vh4ID
            LEFT JOIN h1 h1 ON qd.h1ID = h1.h1ID
            LEFT JOIN button_hover bh ON qd.bhID = bh.bhID
            LEFT JOIN copy_button cb ON qd.copyButtonID = cb.copyButtonID
            LEFT JOIN general_button gb on qd.generalButtonID = gb.generalButtonID
            LEFT JOIN h2 h2 ON qd.h2ID = h2.h2ID
            LEFT JOIN home h ON qd.homeID = h.homeID
            LEFT JOIN next_button nb ON qd.nextButtonID = nb.nextButtonID
            LEFT JOIN options_container oc ON qd.oconID = oc.oconID";
            
  $result = mysqli_query($conn, $query);

  while($row = mysqli_fetch_assoc($result)){
    // button: hover
    $bh_background          = $row['bh_background'];
    $bh_transition          = $row['bh_transition'];
    $bh_transform           = $row['bh_transform'];

    // copy button
    $cb_font_size           = $row['cb_font_size'];
    $cb_color               = $row['cb_color'];
    
    // general button
    $gb_padding             = $row['gb_padding'];
    $gb_font_size           = $row['gb_font_size'];
    $gb_background          = $row['gb_background'];
    $gb_border_radius       = $row['gb_border_radius'];
    $gb_border              = $row['gb_border'];
    $gb_color               = $row['gb_color'];
    $gb_letter_spacing      = $row['gb_letter_spacing'];
    $gb_margin              = $row['gb_margin'];

    // h1
    $h1_font_size           = $row['h1_font_size'];
    $h1_font_weight         = $row['h1_font_weight'];
    $h1_letter_spacing      = $row['h1_letter_spacing'];
    $h1_line_height         = $row['h1_line_height'];
    $h1_text_align          = $row['h1_text_align'];
    $h1_color               = $row['h1_color'];
    $h1_margin              = $row['h1_margin'];

    // h2
    $h2_font_size           = $row['h2_font_size'];
    $h2_font_weight         = $row['h2_font_weight'];
    $h2_text_transform      = $row['h2_text_transform'];
    $h2_color               = $row['h2_color'];
    $h2_text_align          = $row['h2_text_align'];

    // home 
    $home_background        = $row['home_background'];
    $home_logo              = $row['home_logo'];
    $home_title             = $row['home_title'];
    $home_subtitle          = $row['home_subtitle'];
    $home_description       = $row['home_description'];

    // next button
    $nb_font_weight         = $row['nb_font_weight'];
    $nb_background          = $row['nb_background'];
    $nb_color               = $row['nb_color'];     
    $nb_animation           = $row['nb_animation'];     
    
    // options container 
    $ocon_animation         = $row['ocon_animation'];     
    
    // p
    $p_font_size            = $row['p_font_size'];     
    $p_font_weight          = $row['p_font_weight'];     
    $p_text_align           = $row['p_text_align'];     
    $p_color                = $row['p_color'];     
    
    // product container
    $pcon_background        = $row['pcon_background'];     
    $pcon_border_radius     = $row['pcon_border_radius'];     
    $pcon_border            = $row['pcon_border'];     
    $pcon_margin            = $row['pcon_margin'];     
    $pcon_img_width         = $row['pcon_img_width'];     
    $pcon_img_border_radius = $row['pcon_img_border_radius'];     
    $pcon_desc_font_size    = $row['pcon_desc_font_size'];     
    $pcon_desc_color        = $row['pcon_desc_color'];     
    $pcon_desc_font_weight  = $row['pcon_desc_font_weight'];     
    
    // progress
    $progress_background_color = $row['progress_background_color'];
    $progress_border_radius = $row['progress_border_radius'];

    // progress bar
    $pb_width               = $row['pb_width'];
    $pb_height              = $row['pb_height'];
    $pb_background_color    = $row['pb_background_color'];
    $pb_border_radius       = $row['pb_border_radius'];
    $pb_border_color        = $row['pb_border_color'];
    $pb_border_style        = $row['pb_border_style'];
    $pb_position            = $row['pb_position'];
    $pb_top                 = $row['pb_top'];
    $pb_left                = $row['pb_left'];

    // question container
    $qcon_background_color  = $row['qcon_background_color'];
    $qcon_border            = $row['qcon_border'];
    $qcon_padding           = $row['qcon_padding'];
    $qcon_border_radius     = $row['qcon_border_radius'];
    $qcon_letter_spacing    = $row['qcon_letter_spacing'];
    $qcon_font_size         = $row['qcon_font_size'];
    $qcon_color             = $row['qcon_color'];
    $qcon_font_weight       = $row['qcon_font_weight'];
    $qcon_line_height       = $row['qcon_line_height'];
    
    // result subheading
    $rSH_font_weight        = $row['rSH_font_weight'];
    $rSH_font_size          = $row['rSH_font_size'];
    $rSH_color              = $row['rSH_color'];
    
    // result title
    $rTitle_font_weight     = $row['rTitle_font_weight'];
    $rTitle_font_size       = $row['rTitle_font_size'];
    $rTitle_color           = $row['rTitle_color'];
    $rTitle_margin          = $row['rTitle_margin'];

    // spacer
    $spacer_height          = $row['spacer_height'];
    
    // voucher h1
    $vh1_font_size          = $row['vh1_font_size'];
    $vh1_font_weight        = $row['vh1_font_weight'];
    $vh1_letter_spacing     = $row['vh1_letter_spacing'];
    $vh1_line_height        = $row['vh1_line_height'];
    $vh1_color              = $row['vh1_color'];
    
    // voucher h4
    $vh4_font_weight        = $row['vh4_font_weight'];
    $vh4_line_height        = $row['vh4_line_height'];
    $vh4_font_size          = $row['vh4_font_size'];

    // wrapper
    $wrap_animation         = $row['wrap_animation'];
    $wrap_padding           = $row['wrap_padding'];
    $wrap_flex_direction    = $row['wrap_flex_direction'];
  }
?>

@import url("https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap");

body,
html {
  margin: 0;
  padding: 0;
  height: 100%;
  align-items: center;
  justify-content: center;
  font-family: "Inter", sans-serif;
  text-rendering: optimizeSpeed;
}

p {
  color: <?php echo $p_color;?>;
  font-weight: <?php echo $p_font_weight;?>;
  text-align: <?php echo $p_text_align;?>;
  font-size: <?php echo $p_font_size;?>;
}

h1 {
  font-size: <?php echo $h1_font_size;?>;
  font-weight: <?php echo $h1_font_weight;?>;
  letter-spacing: <?php echo $h1_letter_spacing;?>;
  line-height: <?php echo $h1_line_height;?>;
  text-align: <?php echo $h1_text_align;?>;
  color: <?php echo $h1_color;?>;
  margin: <?php echo $h1_margin;?>;
}

h2 {
  text-align: <?php echo $h2_text_align;?>;
  font-size: <?php echo $h2_font_size;?>;
  color: <?php echo $h2_color;?>;
  font-weight: <?php echo $h2_font_weight;?>;
  text-transform: <?php echo $h2_text_transform;?>;
  margin: 0;
}

h3 {
  text-align: center;
  font-size: clamp(20px, 2vw, 26px);
  color: #8e7242;
  font-weight: bold;
  line-height: 1.2em;
  letter-spacing: 1px;
  margin: 0;
}

h4 {
  color: #8e7242;
  text-align: center;
  font-weight: normal;
  line-height: 26px;
  font-size: larger;
}

.space-between {
  justify-content: space-between;
}

.justify-center {
  justify-content: center;
}

.gap20 {
  gap: 20px;
}

.wrapper {
  display: flex;
  flex-direction: <?php echo $wrap_flex_direction;?>;
  flex-wrap: nowrap;
  align-content: center;
  align-items: center;
  max-width: 450px;
  min-width: 280px;
  padding: <?php echo $wrap_padding;?>;
  animation: <?php echo $wrap_animation;?>;
  animation-iteration-count: 1;
  animation-fill-mode: forwards;
}
.no-animate {
  animation: none;
}

.progress-bar {
  width: <?php echo $pb_width;?>;
  background-color: <?php echo $pb_background_color;?>;
  height: <?php echo $pb_height;?>;
  border-radius: <?php echo $pb_border_radius;?>;
  border-color: <?php echo $pb_border_color;?>;
  border-style: <?php echo $pb_border_style;?>;
  position: <?php echo $pb_position;?>;
  top: <?php echo $pb_top;?>;
  left: <?php echo $pb_left;?>;
  transform: translate(-50%, 10px);
}

.progress {
  background-color: <?php echo $progress_background_color;?>;
  height: 100%;
  transition: width 2s ease; /* This line provides the animation */
  border-radius: <?php echo $progress_border_radius;?>;
  width: 0%; /* Ensure the progress bar starts from 0 width */
}

@keyframes fadeInAnimation {
  0% {
    opacity: 0;
    transform: translateY(-20px);
  }
  100% {
    opacity: 1;
    transform: translateY(0);
  }
}

.title {
  text-align: center;
  font-size: clamp(20px, 5vw, 28px);
  font-weight: bold;
  color: #8e7242;
}

.header-container {
  width: 100%;
  margin-bottom: 30px;
}

.next {
  width: 100%;
  animation: <?php echo $nb_animation;?>;
}

.options-container{
  width: 100%;
  animation: <?php echo $ocon_animation;?>;
}

/* .options-container .parent-answer-btn {
  animation: fadeInAnimation ease 1s;
  animation-fill-mode: forwards;
  opacity: 0;
}

.options-container .parent-answer-btn:nth-child(1) {
  animation-delay: 0.1s;
}
.options-container .parent-answer-btn:nth-child(2) {
  animation-delay: 0.2s;
}
.options-container .parent-answer-btn:nth-child(3) {
  animation-delay: 0.3s;
}
.options-container .parent-answer-btn:nth-child(4) {
  animation-delay: 0.4s;
}
.options-container .parent-answer-btn:nth-child(5) {
  animation-delay: 0.5s;
}
.options-container .parent-answer-btn:nth-child(5) {
  animation-delay: 0.6s;
} */

.quiz {
  font-size: 70px;
}

button {
  transition: all 0.5s ease;
  padding: <?php echo $gb_padding;?>;
  width: 100%;
  font-size: <?php echo $gb_font_size;?>;
  background-image: <?php echo $gb_background;?>;
  border-radius: <?php echo $gb_border_radius;?>;
  border: <?php echo $gb_border;?>;
  color: <?php echo $gb_color;?>;
  letter-spacing: <?php echo $gb_letter_spacing;?>;
  margin: <?php echo $gb_margin;?>;
}

button:hover {
  color: #8e7242;
  background-image: <?php echo $bh_background;?>;
  transition: <?php echo $bh_transition;?>;
  -webkit-transform: translateY(-3px);
  transform: <?php echo $bh_transform;?>;
}

button:active {
  background-image: linear-gradient(#f9f6f1, #f9f6f1);
}

.btn-answer.selected {
  color: #8e7242;
  background-image: linear-gradient(#f9f6f1, #f9f6f1);
}

.next {
  font-weight: <?php echo $nb_font_weight;?>;
  background-image: <?php echo $nb_background;?>;
  color: <?php echo $nb_color;?>;
}

.spacer {
  height: <?php echo $spacer_height;?>;
  width: 100%;
}

.q-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  background-color: <?php echo $qcon_background_color;?>;
  border: <?php echo $qcon_border;?>;
  padding: <?php echo $qcon_padding;?>;
  border-radius: <?php echo $qcon_border_radius;?>;
  color: <?php echo $qcon_color;?>;
  position: relative;
  letter-spacing: <?php echo $qcon_letter_spacing;?>;
}

.question-box h3{
  font-size: <?php echo $qcon_font_size;?>;
  font-weight: <?php echo $qcon_font_weight;?>;
  line-height: <?php echo $qcon_line_height;?>;
}

.question-mark {
  position: absolute;
  top: -30px;
}

.bonus-answer-btn.selected,
.conditional-answer-btn.selected,
.parent-answer-btn.selected {
  color: #8e7242;
  background-image: linear-gradient(#f9f6f1, #f9f6f1);
}

/* Results Page */
.no-favorable-products {
  color: #ff0000;
  font-weight: bold;
  font-size: 16px;
  text-align: center;
  margin-top: 20px;
}

.suggested-products {
  display: flex;
  overflow: scroll;
  flex-direction: column;
  -ms-overflow-style: none;
  scrollbar-width: none;
  padding-bottom: var(--mask-height);
  justify-content: flex-start;
}

.masked-overflow {
  --scrollbar-width: 8px;
  --mask-height: 32px;
  overflow-y: auto;
  height: max-content;
  --mask-image-content: linear-gradient(
    to bottom,
    transparent,
    black var(--mask-height),
    black calc(100% - var(--mask-height)),
    transparent
  );
  --mask-size-content: calc(100% - var(--scrollbar-width)) 100%;
  --mask-image-scrollbar: linear-gradient(black, black);
  --mask-size-scrollbar: var(--scrollbar-width) 100%;
  mask-image: var(--mask-image-content), var(--mask-image-scrollbar);
  mask-size: var(--mask-size-content), var(--mask-size-scrollbar);
  mask-position: 0 100%, 100% 100%;
  mask-repeat: no-repeat, no-repeat;
  max-height: 500px;
}

.suggested-products::-webkit-scrollbar {
  display: none;
}

.product-container {
  background: <?php echo $pcon_background;?>;
  border-radius: <?php echo $pcon_border_radius;?>;
  border: <?php echo $pcon_border;?>;
  display: flex;
  flex-direction: column;
  margin: <?php echo $pcon_margin;?>;
  width: 210px;
}

.product-body {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 20px;
}

.product-body p {
  font-size: <?php echo $pcon_desc_font_size;?>;
  color: <?php echo $pcon_desc_color;?>;
  font-weight: <?php echo $pcon_desc_font_weight;?>;
}

.product-container:hover {
  box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
}

.suggested-image {
  width: <?php echo $pcon_img_width;?>;
  border-radius: <?php echo $pcon_img_border_radius;?>;
}

.view-product-button {
  font-size: 11px;
  width: 100%;
  margin-bottom: 0px;
}

.voucher {
  width: 100%;
}

.voucher h1{
  font-size: <?php echo $vh1_font_size;?>;
  font-weight: <?php echo $vh1_font_weight;?>;
  letter-spacing: <?php echo $vh1_letter_spacing;?>;
  line-height: <?php echo $vh1_line_height;?>;
  color: <?php echo $vh1_color;?>;
}

.voucher h4{
  font-weight: <?php echo $vh4_font_weight;?>;
  line-heght: <?php echo $vh4_line_height;?>;
  font-size: <?php echo $vh4_font_size;?>;
}

.nav-buttons {
  width: -webkit-fill-available;
  gap: 10px;
  display: flex;
  flex-direction: column;
  padding: 0px 10px 0px 10px;
}

.result-title h3 {
  font-weight: <?php echo $rTitle_font_weight;?>;
  font-size: <?php echo $rTitle_font_size;?>;
  color: <?php echo $rTitle_color;?>;
  margin: <?php echo $rTitle_margin;?>;
}

.result-title p{
  font-weight: <?php echo $rSH_font_weight;?>;
  font-size: <?php echo $rSH_font_size;?>;
  color: <?php echo $rSH_color;?>;
}

.copy-code {
  display: none;
}

.voucher-code-container {
  display: flex;
  align-items: center;
  gap: 10px;
  justify-content: center;
  margin-bottom: 30px;
}

.fa-regular.fa-clone {
  color: #8e7242;
  font-size: 17px;
}

.fa-solid.fa-check {
  color: #8e7242;
}

/* Home Page Styles*/

.home-custom-bg {
  background-repeat: no-repeat;
  background-size: cover;
  background-position: center center;
  width: 100%;
  height: 100%;
  position: relative;
}

.body-wrapper {
  background-size: cover;
  background-position: center center;
  width: 100%;
  height: 100%;
  display: flex;
  justify-content: center;
}

.bg1 {
  background-image: url("./images/bg-6.jpg");
}

.bg2 {
  background-image: url("./images/bg.jpg");
}

.bg3 {
  background-image: url("./images/bg-2.jpg");
}

.bg4 {
  background-image: url("./images/bg-3.jpg");
}

.bg5 {
  background-image: url("./images/bg-4.jpg");
}

.home-content {
  text-align: center;
  display: flex;
  flex-direction: column;
  align-items: center;
  max-width: 450px;
  margin: auto;
  justify-content: center;
}

.home-wrapper {
  display: flex;
  flex-direction: column;
  flex-wrap: nowrap;
  align-content: center;
  justify-content: space-between;
  align-items: center;
  width: 450px;
}

.home-custom-bg .content {
  text-align: center;
  display: flex;
  flex-direction: column;
  align-items: center;
  max-width: 450px;
  margin: auto;
  height: -webkit-fill-available;
}

.home-custom-bg .wrapper {
  display: flex;
  flex-direction: column;
  flex-wrap: nowrap;
  align-content: center;
  justify-content: space-between;
  align-items: center;
}

.countries-container {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  width: 450px;
}

.country-card {
  width: 200px;
  padding: 20px;
  margin: 10px;
  border: 2px solid #d6b77e;
  border-radius: 7.75px;
  background: #f9f6f1;
  cursor: pointer;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  text-decoration: none;
  color: #8e7242;
  text-align: center;
  font-weight: 700;
  box-shadow: 0px 5px 10px -5px rgba(0, 0, 0, 0.5);
  transition: transform 0.2s;
}

.country-card:hover {
  transform: translate(0, -10px);
}

.country-flag {
  width: 150px;
  height: auto;
  margin-bottom: 10px;
}

@media only screen and (max-width: 768px) {
  .progress-bar {
    width: 280px;
  }

  .countries-container {
    width: 400px;
  }

  .country-card {
    width: 120px;
    height: 125px;
  }

  .country-flag {
    width: 80px;
  }

  .display-none {
    display: none;
  }
}

/* Admin Dashboard */
.ADALogo {
  width: clamp(40px, 40%, 80px);
  height: auto;
  position: absolute;
  top: clamp(1%, 3%, 5%);
  right: clamp(1%, 3%, 5%);
}

.login-title {
  margin-bottom: 20px;
}

.login-title h1 {
  color: white;
  font-size: 32px;
}

.dashboard-login {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin: auto;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}

.login-form {
  background-color: rgba(255, 255, 255, 0.2);
  padding: 30px;
  border-radius: 5px;
  width: 50vw;
}

.container input {
  width: 100%;
  height: 48px;
  padding: 10px;
  margin-bottom: 30px;
  box-sizing: border-box;
  border-radius: 4px;
  border: none;
}

.container input:focus {
  border: none;
}

*:focus {
  outline: none;
}

.loginpage-button {
  background-color: rgb(97, 119, 196);
  color: #ffffff;
  width: 100%;
  height: 48px;
  border-radius: 4px;
  border-color: none;
  border: none;
}

.password-container {
  position: relative;
}

.password-container input {
  width: 100%;
  height: 48px;
  padding: 10px;
  margin-bottom: 30px;
  box-sizing: border-box;
  border-radius: 4px;
  border: none;
}

.password-container button {
  position: absolute;
  right: 10px;
  top: 33%;
  transform: translateY(-50%);
  background-color: transparent;
  border: none;
  cursor: pointer;
}

#eye-icon {
  color: #9ba3be;
}

/* Admin Dashboard Styles */

.page-link {
  background-color: #495057 !important;
  border-color: #343a40 !important;
}