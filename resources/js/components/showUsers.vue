<template>
   <div class="row">
        <div class="col-4">
            <label for="filtro" class="form-label">Filtro de Busqueda</label>
            <input type="text" class="form-control" id="filtro" @keypress="filtro($event)" 
                v-model="palabraClave" placeholder="Palabra Clave">
        </div>
        <div class="col-2 offset-md-10 card-body d-flex justify-content-between align-items-right" 
            style="margin-left:auto; margin-right:0;">
            <button type="button" @click="newUser()" class="col-auto btn btn-primary" 
                data-bs-toggle="modal" data-bs-target="#exampleModal" style="margin-left:auto; margin-right:0;">
              Nuevo Usuario
            </button>    
        </div>       

        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Telefono</th>
                    <th>Email</th>
                    <th>Fecha Creaci&oacute;n</th>
                    <th>Fecha Modificaci&oacute;n</th>
                    <th>Acci&oacute;n</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(user, index) in users[0]">
                    <td>{{ user.id }}</td>
                    <td>{{ user.firstname }}</td>
                    <td>{{ user.lastname }}</td>
                    <td>{{ user.phone }}</td>
                    <td>{{ user.email }}</td>
                    <td>{{ user.created_at }}</td>
                    <td>{{ user.updated_at }}</td>
                    <td>
                        <button @click="dataUser(user)" class="btn btn-primary" 
                            data-bs-toggle="modal" data-bs-target="#exampleModal">
                            <i class="fa fa-trash">Editar</i>
                        </button>
                        <button @click.prevent="deleteUser(user,index)"
                                class="btn btn-danger">
                            <i class="fa fa-trash">Eliminar</i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
        <div>
            <!-- Modal Create or Update -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                    <div class="mb-3">
                       <label class="form-label">{{ message }}</label>
                       <input type="hidden" name="customfield" class="form-control" v-model="user.id">

                  </div>
                  <div class="modal-body">
                    <div class="mb-3">
                      <label for="firstname" class="form-label">Nombre</label>
                      <input type="text" class="form-control" id="firstname" placeholder="Nombre" v-model="user.firstname" required>
                    </div>
                    <div class="mb-3">
                      <label for="lastname" class="form-label">Apellido</label>
                      <input type="text" class="form-control" id="lastname" placeholder="Apellido" v-model="user.lastname" required>
                    </div>
                    <div class="mb-3">
                      <label for="phone" class="form-label">Telefono</label>
                      <input type="text" class="form-control" id="phone" placeholder="Telefono" v-model="user.phone" required>
                    </div>
                    <div class="mb-3">
                      <label for="email" class="form-label">Email</label>
                      <input type="email" class="form-control" id="email" placeholder="Email" v-model="user.email" required>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" @click.prevent="enviarData()">Enviar</button>
                  </div>
                </div>
              </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    export default {
        name: 'users',
        data () {
            return {
                users: [],
                palabraClave:"",
                user: {
                    id:"",
                    firstname :"",
                    lastname :"",
                    phone :"",
                    email :""
                },
                message : "",
                id : this.$store.getters.getUpdateUser
            };
        },
        created () {
            this.getAllUsers();
        },
        methods: {
            getAllUsers () {
                setInterval(() =>{
                    if(this.$store.getters.getUpdateUsers){
                        var filter = "";
                        if(this.palabraClave.trim() != ""){
                            filter = "filter/"+this.palabraClave.trim();
                        }else{
                            filter = "";
                        }
                        axios
                        .get('api/users'+filter)
                        .then(response => {
                            this.users = [response.data];
                            this.$store.dispatch("addUser", false);
                        }).catch(error => {
                            console.log(error);
                        });
                    }                    
                },2000);
            },            
            deleteUser(user,index) {
                axios
                .delete('api/users/' + user.id)
                .then(response => {
                    this.$delete(this.users[0], index);
                    setTimeout(this.getUpdate, 1000);
                });
            },
            newUser(){
                this.message = "";
                this.user.firstname = "";
                this.user.lastname = "";
                this.user.phone = "";
                this.user.email = "";
            },
            dataUser(user) {
                axios
                .get('api/users/'+user.id)
                .then(response => {
                    this.user.id = user.id;
                    this.user.firstname = response.data.firstname;
                    this.user.lastname = response.data.lastname;
                    this.user.phone = response.data.phone;
                    this.user.email = response.data.email;
                }).catch(error => {
                    alert(error);
                });
            },
            filtro: function($event) {
                this.$store.dispatch("addUser", true);
            },
            enviarData(){
                //Validaciones
                if (this.validationData(this.user)) {
                    this.message = "Ingresa todos los datos para crear un usuario";
                }else{
                    if(this.user.id != ""){
                        this.updateUser();
                    }else{
                        this.createUser();
                    }
                }
            },
            updateUser() {
                axios
                .put('api/users/'+this.user.id, {
                    firstname:  this.user.firstname,
                    lastname:   this.user.lastname,
                    phone:      this.user.phone,
                    email:      this.user.email
                })
                .then(response => {
                    this.message = "Actualizacion Exitosa";
                    this.user.id = "";
                    this.user.firstname = "";
                    this.user.lastname = "";
                    this.user.phone = "";
                    this.user.email = "";
                    this.$store.dispatch("addUser", true);
                }).catch(error => {
                    alert(error);
                });
            },
            createUser() {
                axios
                .post('api/users', {
                    firstname:  this.user.firstname,
                    lastname:   this.user.lastname,
                    phone:      this.user.phone,
                    email:      this.user.email
                })
                .then(response => {
                    this.message = "Registro Exitoso";
                    this.user.id = "";
                    this.user.firstname = "";
                    this.user.lastname = "";
                    this.user.phone = "";
                    this.user.email = "";
                    this.$store.dispatch("addUser", true);
                }).catch(error => {
                    alert(error);
                });
            },
            validationData(obj) {
                for(var key in obj) {
                    if(key != 'id'){
                        if(obj[key] == "" || obj[key] == null) {
                            return true
                        }
                    }                    
                }
                return false;
            }
        },
    };
</script>
