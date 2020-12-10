<p>
    Olá {{  $usuario->nome }},<br>
    Obrigado por criar uma conta conosco. Não esqueça de completar seu cadastro!     <br>
    Por favor, clique no link abaixo ou copie-o para a barra de endereços do seu navegador para confirmar seu endereço de e-mail:
    <a href="{{ config("app.frontend_url") }}/#/auth/login?token={{ $usuario->token }}" target="_blank" data-saferedirecturl="{{ config("app.frontend_url") }}/#/auth/login?token={{ $usuario->token }}">Confirme meu endereço de e-mail </a>
     
</p>
