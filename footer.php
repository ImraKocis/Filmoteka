<!-- Footer -->
        <footer>
            <div class="container">
                <div class="row">
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <div class="footer-content">
                            <div class="footer-head">
                                <?php dynamic_sidebar('footer-sidebar1'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <div class="footer-content">
                            <div class="footer-head">
                                <?php dynamic_sidebar('footer-sidebar2'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <div class="footer-content">
                            <div class="footer-head">
                                <?php dynamic_sidebar('footer-sidebar3'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <div class="footer-content">
                            <div class="footer-head">
                                <?php dynamic_sidebar('footer-sidebar4'); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <p class="copyright text-muted">Copyright &copy; Imra Kočiš <?php echo date("Y.") ?></p>
            </div>
        </footer>
        <?php wp_footer(); ?>
    </body>
</html>