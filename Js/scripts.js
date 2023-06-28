import { initializeApp } from "https://www.gstatic.com/firebasejs/9.19.1/firebase-app.js";
import { getAnalytics } from "https://www.gstatic.com/firebasejs/9.19.1/firebase-analytics.js";
import { getAuth, sendPasswordResetEmail , createUserWithEmailAndPassword, signInWithEmailAndPassword } from "https://www.gstatic.com/firebasejs/9.19.1/firebase-auth.js";

$.ajax({
    url: '/PHP/sessao.php',
    type: 'POST',
    data: {
        tipo: 'login'
    },
    success: (res) => {
        if (res == 'Há sessão') {
            window.location.href = "/HTML/telalog.html"
        }
    }
});
// Firebase configurações
const firebaseConfig = {
    apiKey: "AIzaSyBMI35AHm1xLS6b6NWE1HVz2Al4fJZjTmc",
    authDomain: "login-a95cc.firebaseapp.com",
    projectId: "login-a95cc",
    storageBucket: "login-a95cc.appspot.com",
    messagingSenderId: "460090545319",
    appId: "1:460090545319:web:b93a6f0bc136da8687f782"
};
//Chamada de botões e constantes necessarias

const btn = document.querySelector('.Btn_refresh');
const wrapper = document.querySelector('.wrapper');
const loginlink = document.querySelector('.login-link');
const registerlink = document.querySelector('.register-link');
const btnPopup = document.querySelector('.btn_login');
const iconClose = document.querySelector('.icon-close');
var usuario = document.getElementById('usuario_registro');
var email_registro = document.getElementById('email_registro');
var passwords_registro = document.getElementById('senha_registro');
var passwords_login = document.getElementById('senha_entrada');
var email_login = document.getElementById('email_entrada');
const login_name = document.querySelector('.login-name');
const loading = document.querySelector('.loading.login');
const register_name = document.querySelector('.register-name');
const loading2 = document.querySelector('.loading.register');
const check = document.getElementById('checkbox');
const app = initializeApp(firebaseConfig);
const analytics = getAnalytics(app);
const auth = getAuth();

// Funções dos botões
registerlink.addEventListener('click', () => {
    wrapper.classList.add('active');
});

loginlink.addEventListener('click', () => {
    wrapper.classList.remove('active');
})
//Botão de login
btnPopup.addEventListener('click', () => {
    wrapper.classList.add('active-popup');
});
//Ativa o popup de login e registro
iconClose.addEventListener('click', () => {
    wrapper.classList.remove('active-popup');
});
//Vai recarregar a página
btn.addEventListener('click', () => {
    location.reload();
})

let file = document.getElementById('flImage');

//  Funções firebase
window.registro = (e) => {
    e.preventDefault();

    register_name.classList.add('desactive');

    loading2.classList.add('active');

    var obj = {
        usuario: usuario.value,
        email: email_registro.value,
        passwords: passwords_registro.value,
        file: file.files[0]
    };

    if (!obj.file) {
        alert('Por favor, insira uma foto de usuário!');
        register_name.classList.remove('desactive');
        loading2.classList.remove('active');
        return; // Impede que o registro continue
    }
    
    if (obj.usuario == undefined || obj.usuario == '') {
        alert('Por favor, insira seu Nome!');
        register_name.classList.remove('desactive');
        loading2.classList.remove('active');
        return; // Impede que o registro continue
    } else if (obj.email == undefined || obj.email == '') { 
        alert('Por favor, insira seu Email!'); 
        register_name.classList.remove('desactive');
        loading2.classList.remove('active');
        return; // Impede que o registro continue
    } else if (obj.passwords == undefined || obj.passwords == '') { 
        alert('Por favor, insira sua senha!');
        register_name.classList.remove('desactive');
        loading2.classList.remove('active');
        return; // Impede que o registro continue
    }

    const extensao = obj.file.type.replace('image/', '');
    if (extensao == 'jpg' || extensao == 'jpeg' || extensao == 'png' || extensao == 'ico') { 
        $.ajax({
            url: '/PHP/registro.php',
            type: 'POST',
            data: {
                tipo: 'usuario_test',
                usuario: obj.usuario,
                email: obj.email,
            },
            success: (response) => {
                if (response == 'usuário já existente') {
                    alert('Nome de usuário já existente. Se possível use nome e sobrenome!');
                    return;
                }
    
                // Usuário não existe, então continua com o registro
                createUserWithEmailAndPassword(auth, obj.email, obj.passwords)
                    .then((success) => {
                        var form = document.getElementById('registroForm');
                        var formData = new FormData(form);
                        formData.append('tipo', 'usuario');
    
                        fetch(form.action, {
                            method: form.method,
                            body: formData
                        })
                            .then(response => {
                                if (response.ok) {
                                    return response.text(); // Obtém o conteúdo da resposta como texto
                                } else {
                                    throw new Error('Erro ao receber o arquivo');
                                }
                            })
                            .then(data => {
                                // Exibe a resposta no console do navegador, porém esta resposta vem do arquivo php
    
                                // Faça algo com o resultado (PromisseResult) aqui
                            })
                            .catch(error => {
                                console.error('Erro ao enviar o formulário', error);
                            });
                         alert('Usuário Cadastrado com sucesso!');
                         wrapper.classList.remove('active');
                    })
                    .catch((e) => {
                        if (e == 'FirebaseError: Firebase: Error (auth/email-already-in-use).') {
                            alert('Erro: Usuário já cadastrado!');
                            wrapper.classList.remove('active');
                            register_name.classList.remove('desactive');
                            loading2.classList.remove('active');
                            return;
                        } else if (e == 'FirebaseError: Firebase: Password should be at least 6 characters (auth/weak-password).') {
                            alert('Por favor, insira uma senha que contenha, no mínimo 6 dígitos!');
                            register_name.classList.remove('desactive');
                            loading2.classList.remove('active');
                            return;
                        } else if (e == 'FirebaseError: Firebase: Error (auth/missing-email).') {
                            alert('Por favor, insira um E-mail válido!');
                            register_name.classList.remove('desactive');
                            loading2.classList.remove('active');
                            return;
                        } else {
                            register_name.classList.remove('desactive');
                            loading2.classList.remove('active');
                            alert('Register Error: ' + e);
                            return;
                        }
                    });
            }
        });
    } else {
        alert('Por favor, insira sua foto nos formatos: PNG, JPEG, JPG');
        register_name.classList.remove('desactive');
        loading2.classList.remove('active');
        console.log(extensao);
        return; // Impede que o registro continue
    }
    

};

window.rec_senha = (e) => {
    var email = $('#email_entrada').val();
    
    if (email == '') {
        alert('Por favor insira um email de recuperação!');
        return;
    };

    sendPasswordResetEmail(auth, email)
    .then(() => {
        alert('Por favor verifique seu email para troca de senha!');
    })
    .catch((error) => {
        const errorCode = error.code;
        const errorMessage = error.message;
    });
};

window.login = (e) => {
    e.preventDefault();

    login_name.classList.add('desactive');

    loading.classList.add('active');

    var obj = {
        email: email_login.value,
        passwords: passwords_login.value
    }

    signInWithEmailAndPassword(auth, obj.email, obj.passwords)
        .then((success) => {
            $.ajax({
                url: '/PHP/newsession.php',
                type: 'POST',
                data: {
                    check: check.checked,
                    email_login: obj.email,
                    senha_login: obj.passwords
                },
                sync: false,
                success: (res) => {
                    if (res == 'sessão criada com sucesso') {
                        window.location.href = "/HTML/telalog.html"
                    } else if (res == 'Error') {
                        alert('Erro ao criar sua sessão!');
                    }
                }
            });
        })
        .catch((e) => {
            if (e == 'FirebaseError: Firebase: Error (auth/wrong-password).') {
                alert('Senha ou usuário não estão corretos, por favor tente novamente!');
                login_name.classList.remove('desactive');
                loading.classList.remove('active');
            } else if (e == 'FirebaseError: Firebase: Error (auth/user-not-found).') {
                alert('Usuário não encontrado!');
                login_name.classList.remove('desactive');
                loading.classList.remove('active');
                wrapper.classList.add('active');
            } else if (e == 'FirebaseError: Firebase: Error (auth/missing-password).') {
                alert('Por favor, insira sua senha!');
                login_name.classList.remove('desactive');
                loading.classList.remove('active');
                wrapper.classList.add('active');
            } else if (e == 'FirebaseError: Firebase: Error (auth/invalid-email).') {
                alert('Por favor, insira um E-mail válido!');
                login_name.classList.remove('desactive');
                loading.classList.remove('active');
                wrapper.classList.add('active');
            } else {
                login_name.classList.remove('desactive');
                loading.classList.remove('active');
                alert('Login error: ' + e);
            }
        });
};