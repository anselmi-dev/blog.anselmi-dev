{{--
    La primera vez, el menú lateral arranca contraído (solo íconos) en
    escritorio; después se respeta lo que elija cada uno.
--}}
<script>
    try {
        if (window.innerWidth >= 1024 && localStorage.getItem('isOpenDesktop') === null) {
            localStorage.setItem('isOpenDesktop', 'false');
            localStorage.setItem('isOpen', 'false');
        }
    } catch (error) {}
</script>
