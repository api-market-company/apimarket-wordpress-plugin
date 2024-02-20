<!-- API MARKET Documentation -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" >
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>
<div class="cf7anyapi_doc">
    <h3><?php esc_html_e( 'API MARKET Documentation', 'contact-form-to-any-api' ); ?></h3>
    <div class="row">
    <div class="col-2 tab">
        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
        <a class="nav-link active" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true"><?php esc_html_e( 'How to configure', 'contact-form-to-any-api' ); ?></a>
        <a class="nav-link" id="v-pills-logs-tab" data-toggle="pill" href="#v-pills-logs" role="tab" aria-controls="v-pills-logs" aria-selected="false"><?php esc_html_e( 'Logs', 'contact-form-to-any-api' ); ?></a>
        <a class="nav-link" id="v-pills-entries-tab" data-toggle="pill" href="#v-pills-entries" role="tab" aria-controls="v-pills-entries" aria-selected="false"><?php esc_html_e( 'Entries', 'contact-form-to-any-api' ); ?></a>
        <a class="nav-link" id="v-pills-validation-preview-tab" data-toggle="pill" href="#v-pills-validation-preview" role="tab" aria-controls="v-pills-validation-preview" aria-selected="false"><?php esc_html_e( 'Validation and Preview', 'contact-form-to-any-api' ); ?></a>
        <a class="nav-link" id="v-pills-cf7-hidden-field-tab" data-toggle="pill" href="#v-pills-cf7-hidden-field" role="tab" aria-controls="v-pills-cf7-hidden-field" aria-selected="false"><?php esc_html_e( 'CF7 Hidden Fields', 'contact-form-to-any-api' ); ?></a>
        <a class="nav-link" id="v-pills-contact-us-tab" data-toggle="pill" href="#v-pills-contact-us" role="tab" aria-controls="v-pills-contact-us" aria-selected="false"><?php esc_html_e( 'Contact Us', 'contact-form-to-any-api' ); ?></a>
        </div>
    </div>
    <div class="col-7 tab">
        <div class="tab-content" id="v-pills-tabContent">
        <!-- cf7 API -->
        <div class="tab-pane fade show active cf7anyapi_full_width" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
            <h5><?php esc_html_e( 'API MARKET', 'contact-form-to-any-api' ); ?></h5>
            <ol>
                <li><?php esc_html_e( 'Click add new CF7 API and give the suitable API title', 'contact-form-to-any-api' ); ?></li>
                <li><?php esc_html_e( 'Please select a form which you would like to connect with API', 'contact-form-to-any-api' ); ?></li>
                <li><?php esc_html_e( 'Enter your API URL', 'contact-form-to-any-api' ); ?></li>
                <li><?php esc_html_e( 'Add header request like below', 'contact-form-to-any-api' ); ?></li>

            <code>
        <pre>
    Authorization: MY_API_KEY
    Authorization: Bearer xxxxxxx
    Authorization: Basic xxxxxx
    Content-Type: application/json
        </pre>
            </code>
            <p><b>Authorization having Username & Password with Base64 ?</br>
               to convert online <a href="https://www.base64encode.net/" target="_blank">click here</a> and put it on header </b></p>
            <b>Example</b><code>
                <pre>
    Authorization: Basic ' . base64_encode( YOUR_USERNAME . ':' . YOUR_PASSWORD )
    Content-Type: application/json 
                </pre>
            </code>
            <b>After convert put it on header like below</b>
            <code>
                <pre>
    Authorization: Basic c2FsdXRlLXZldGVyYW5zLWFwaSA6IDBjd1NURENTcE91MUNOQXFVRFFmajdN
    Content-Type: application/json
                </pre>
            </code> 
            <li><?php esc_html_e( 'Then you have to select your Input Type JSON OR GET/POST', 'contact-form-to-any-api' ); ?></li>
                <li><?php esc_html_e( 'Select your API Method POST or GET', 'contact-form-to-any-api' ); ?></li>
                <li><?php esc_html_e( 'Map your Fields with your API KEYS', 'contact-form-to-any-api' ); ?> </li>
                <li><?php esc_html_e( 'Save your API configuration', 'contact-form-to-any-api' ); ?> </li>
            </ol>
        </div>
        <!-- Logs -->
        <div class="tab-pane fade cf7anyapi_full_width" id="v-pills-logs" role="tabpanel" aria-labelledby="v-pills-logs-tab">
        <h5>Logs</h5>
            <ol>
                <li>After submitting data you can see your data in <b>Logs</b> tab.</li>
                <li>You can see your API logs and its data which is submitted by user </li>
                <li>You can see your <b>API response too</b>.</li>
                Ex. <img src="<?php echo plugins_url().'/contact-form-to-any-api/admin/images/logs.png';?>" alt="" style="height:100%; width:100%;">
            </ol>
        </div>

        <!-- entries -->
        <div class="tab-pane fade" id="v-pills-entries" role="tabpanel" aria-labelledby="v-pills-entries-tab">
        <h5>Entries</h5>
            <ol>
                <li>Select the form and its data will display.</li>               
                <li>You can download your data in <b>CSV</b>, <b>Excel</b>, <b>PDF</b> and also you can <b>Print</b> your data.</li>
                Ex. <img src="<?php echo plugins_url().'/contact-form-to-any-api/admin/images/entries.png';?>" alt="" style="height:100%; width:100%;">
            </ol>
            
        </div>

        <!-- Validation and client preview -->
        <div class="tab-pane fade cf7anyapi_full_width" id="v-pills-validation-preview" role="tabpanel" aria-labelledby="v-pills-validation-preview">
            <h5>Client validation</h5>
            When you need to validate NSS, CURP, or RFC, simply add the respective CSS classes 'nss', 'curp', and 'rfc'. This action will enable typical client-side validation for these formats. Additionally, we offer other utility CSS validation tools for phone numbers, regular expressions (labeled as 'RegEx-Expression'), and email verification (identified as 'emailVerify').
            <h5>AJAX preview</h5>
            You can create a preview using the shortcut [apimarket_preview_modal]. It provides you with the AJAX code and a modal to show a preview of the answer.
        </div>

        <!-- CF7 Hidden field -->
         <div class="tab-pane fade cf7anyapi_full_width" id="v-pills-cf7-hidden-field" role="tabpanel" aria-labelledby="v-pills-cf7-hidden-field">
            <h5><?php esc_html_e( 'How to use CF7 Hidden fields', 'contact-form-to-any-api' ); ?></h5><br>
            <p class="pro_tab_description">Hidden field without value :  <strong> [hidden tracking-id] </strong></p>
            <p class="pro_tab_description">Hidden field with Default value : <strong>[hidden tracking-id default "12345"] </strong></p>
            <p class="pro_tab_description">Hidden field with fix/static value : <strong> [hidden tracking-id "12345"] </strong> </p>
            <p class="pro_tab_description">Hidden field is important part whenever we want to send data to API. Many API has parameter that need to send with static value in that case we can create hidden field and put static value and simply Map Hidden field with API mapping Key</p>
         </div>
         <!-- contact us -->
         <div class="tab-pane fade cf7anyapi_full_width" id="v-pills-contact-us" role="tabpanel" aria-labelledby="v-pills-contact-us-tab">
        <h5>Contact Us</h5> <br>
           <h5>Email : <a href="mailto:abdiel@apimarket.mx">abdiel@apimarket.mx</a></h5>
           <p>Need Help with Plugin Integration ? <a target="_blank" href="https://apimarket.mx/contacto">Click to Connect us</a></p>
        </div>
        </div>
    </div>

</div>