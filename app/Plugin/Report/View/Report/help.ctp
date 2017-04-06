<div class="row two-column with-right-sidebar">
    <div class="obts index span9">
        <h2><?php echo __('Objective Based Training');?> <?php echo __('Help'); ?></h2>
        
        <p>The Objective Based Training section of SafeTrain is used by GPGT (San Angelo Proving Grounds) to track
        Associate Signoff and Compliance for all safety/job related work instructions(OBTs and JSAs).
        </p>
        
        <h3>My Training</h3>
        <p>
            The <strong>My Training</strong> screen lists all Core OBTs for your department(s) and 
            any additional Non-Core OBTs that you have completed.  Any Core OBTs 
            that you have not started will show a 
            <a class="btn btn-mini btn-danger" class="#">Begin OBT</a> button.
        </p>        
        <p>
            Any OBTs Signoffs that are expiring or have expired will show a 
            <a class="btn btn-mini btn-warning" class="#">Expiring</a> or 
            <a class="btn btn-mini btn-warning" class="#">Expired</a> button.  To initiate a renewal,
            simply click on the button and it will change to 
            <a class="btn btn-mini btn-info" href="#">In Progress</a>.
        </p> 
        <p>
            If your <strong>My Training</strong> screen lists a Core OBT that you know is not required for your 
            job function, please see your supervisor.
        </p>      

        <h3>Getting Signed Off</h3>
        
        <ol>
            <li>Find the required OBT from the <strong>List of OBTs</strong> screen.</li>
            <li>Scroll down and click <a class="btn btn-mini btn-primary" href="#">Begin OBT</a>.</li>
            <li>Review the OBT, and contact one of the listed SME (Subject Matter Experts).</li>
        </ol>

        <h3>Subject Matter Experts</h3>
        
            <p>If you are a SME, you signoff Associates by clicking on <strong>SME Signoff</strong> from the right
            side menu.  You will see a list of all requested signoffs for OBTs that you are listed as the SME.</p>

            <ol>
                <li>From this list of requested signoffs, find the Associate that is requesting signoff and click the <a href="#" class="btn btn-mini btn-primary">Begin</a>
            button.</li>
                <li>Observe the Associate as they perform the task.</li>
                <li>Check the 'I certify...' box and click the <a class="btn btn-mini btn-primary" href="#">Signoff</a> button.</li>
            </ol>
                        
            <p>The Associate is now signed off!</p>
            
        <h3>Supervisors</h3>
        
            <p>If you are a supervisor you will see additional Department related links in the right side menu.</p>
            <p>The <strong>Core OBTs</strong> screen lets you add and remove OBTs as core for your department.</p>
            <p>The <strong>Exemptions</strong> screen will allow you to make individual Associates exempt from certain
            Core OBTs.</p>
        <h3>Reports</h3>
        
            <p>The reporting links on the right side menu can be used to see OBT compliance at the Organization, Department, and Associate 
            levels.</p>

    </div>
    <?php echo $this->element( 'sidebar' );?>
</div>
