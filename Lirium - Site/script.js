const header = document.querySelector("header");

window.addEventListener("scroll", () => {

    if(window.scrollY > 50){
        header.classList.add("scrolled");
    }else{
        header.classList.remove("scrolled");
    }

});

   function enviarMensagem(event){

    event.preventDefault();


    let nome = document.getElementById("user_name").value;
    let email = document.getElementById("email").value;
    let assunto = document.getElementById("assunto").value;
    let mensagem = document.getElementById("mensagem").value;


    if(nome === "" || email === "" || assunto === "" || mensagem === ""){

        Swal.fire({
            icon:"warning",
            title:"Campos vazios!",
            text:"Preencha todos os campos antes de enviar."
        });

        return;
    }


    Swal.fire({
        icon:"success",
        title:"Mensagem enviada!",
        text:"Entraremos em contato em breve.",
        showConfirmButton:false,
        timer:3000
    });


    document.querySelector("form").reset();

}



    emailjs.init({
        publicKey: 'skKNMNCdwCXdar76A'
    });

    const btn = document.getElementById('FomularioEmail');

    btn.addEventListener('submit', function(event) {
        event.preventDefault();

        const serviceID = 'service_skgh8ou';
        const templateID = 'template_1vswxyn';

        emailjs.sendForm(serviceID, templateID, this)
            .then(() => {
                Swal.fire({
                    icon:"success",
                    title:"Mensagem enviada!",
                    text:"Entraremos em contato em breve.",
                    showConfirmButton:false,
                    timer:3000
                });
                btn.reset();
            })
            .catch((err) => {
                console.error('Erro ao enviar:', err);
                Swal.fire({
                    icon:"success",
                    title:"Mensagem enviada!",
                    text:"Entraremos em contato em breve.",
                    showConfirmButton:false,
                    timer:3000
                });
                
            });
    });
