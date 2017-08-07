<script>
    $(function() {
        window.opener.$("input[name=ipin_token]").val("{{ $ipin_token }}");

        window.close();
    });
</script>