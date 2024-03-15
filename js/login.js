function Toast(title, icon) {
    Swal.fire({
        toast: true,
        position: "top",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        icon: icon,
        title: title,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        }
    });
}

$('#formLogin').on('submit', (e) => {
    e.preventDefault();
    let user = $('#username').val()
    let pass = $('#password').val()
    if (user == "" || pass == "") {
        return Toast('Ingresa los campos', 'error')
    }
    $.ajax({
        type: "POST",
        url: "api/user.php",
        data: {
            username: user,
            password: pass
        },
    }).done((res) => {
        let response = JSON.parse(res);
        localStorage.setItem('token', response.token)
        return location.href = "clientes.html";
    }).fail((err) => {
        console.error('error', err);
        Toast('El usuario o contraseña no es correcto, verifica tus datos', 'error')
    })
})