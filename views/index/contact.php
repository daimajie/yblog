<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;


?>
<div class="row">

    <!-- post content -->
    <div class="col-lg-12 blog__content mb-30">
        <div class="row justify-content-md-center">
            <div class="col-lg-8">

                <h3>Drop Us a Message</h3>
                <p>Don't hesitate to get in touch. We will reply you as soon as possible.</p>

                <!-- Contact Form -->
                <form id="contact-form" class="contact-form mt-30 mb-30" method="post" action="#">
                    <div class="contact-name">
                        <label for="name">Name <abbr title="required" class="required">*</abbr></label>
                        <input name="name" id="name" type="text">
                    </div>
                    <div class="contact-email">
                        <label for="email">Email <abbr title="required" class="required">*</abbr></label>
                        <input name="email" id="email" type="email">
                    </div>
                    <div class="contact-subject">
                        <label for="email">Subject</label>
                        <input name="subject" id="subject" type="text">
                    </div>
                    <div class="contact-message">
                        <label for="message">Message <abbr title="required" class="required">*</abbr></label>
                        <textarea id="message" name="message" rows="7" required="required"></textarea>
                    </div>

                    <input type="submit" class="btn btn-lg btn-color btn-button" value="Send Message" id="submit-message">
                    <div id="msg" class="message"></div>
                </form>

            </div>
        </div>
    </div> <!-- end col -->

</div>
