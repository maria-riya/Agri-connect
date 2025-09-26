
<?php
// footer.php
?>
    </main>
    <footer style="text-align:center; margin-top:30px; padding:10px; background:#eee;">
        <p>&copy; <?php echo date("Y"); ?> The Tuber Cart. All Rights Reserved.</p>
    </footer>
    </div> <!-- container -->
<?php
$isGuest = (strpos($_SERVER['PHP_SELF'], '/guest/') !== false);
$jsPrefix = $isGuest ? '../' : '';
?>
<script src="<?= $jsPrefix ?>https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
