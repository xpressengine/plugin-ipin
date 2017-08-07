<form name="kcbIpinForm" method="post" action="{{ $env->getAction() }}">
    <input type="hidden" name="IDPCODE" value="V" />
    <input type="hidden" name="IDPURL" value="{{ $env->getIdpUrl() }}" />
    <input type="hidden" name="CPCODE" value="{{ $env->getCode() }}" />
    <input type="hidden" name="CPREQUESTNUM" value="{{ $data['current_time'] }}" />
    <input type="hidden" name="RETURNURL" value="{{ route('plugin.ipin.callback') }}" />
    <input type="hidden" name="WEBPUBKEY" value="{{ $data['pubkey'] }}" />
    <input type="hidden" name="WEBSIGNATURE" value="{{ $data['signature'] }}" />
</form>

<script>
    document.kcbIpinForm.submit();
</script>