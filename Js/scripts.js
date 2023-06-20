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

    var obj = {
        usuario: usuario.value,
        email: email_registro.value,
        passwords: passwords_registro.value,
        file: file.files[0]
    };

    if (!obj.file) {
        alert('Por favor, insira uma foto de usuário!');
        return; // Impede que o registro continue
    }

    $.ajax({
        url: '/PHP/registro.php',
        type: 'POST',
        data: {
            tipo: 'usuario_test',
            usuario: obj.usuario
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
                    } else if (e == 'FirebaseError: Firebase: Password should be at least 6 characters (auth/weak-password).') {
                        alert('Por favor, insira uma senha que contenha, no mínimo 6 dígitos!');
                    } else if (e == 'FirebaseError: Firebase: Error (auth/missing-email).') {
                        alert('Por favor, insira um E-mail válido!');
                    } else {
                        alert('Register Error: ' + e);
                    }
                });
        }
    });
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
            } else if (e == 'FirebaseError: Firebase: Error (auth/user-not-found).') {
                alert('Usuário não encontrado!');
                wrapper.classList.add('active');
            } else if (e == 'FirebaseError: Firebase: Error (auth/missing-password).') {
                alert('Por favor, insira sua senha!');
                wrapper.classList.add('active');
            } else if (e == 'FirebaseError: Firebase: Error (auth/invalid-email).') {
                alert('Por favor, insira um E-mail válido!');
                wrapper.classList.add('active');
            } else {
                alert('Login error: ' + e);
            }
        })
};