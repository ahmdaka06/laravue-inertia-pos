<template>
    <Head>
        <title>Add New User - Aplikasi Kasir</title>
    </Head>
    <main class="c-main">
        <div class="container-fluid">
            <div class="fade-in">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card border-0 rounded-3 shadow border-top-purple">
                            <div class="card-header">
                                <span class="font-weight-bold"><i class="fa fa-user"></i> ADD NEW USER</span>
                            </div>
                            <div class="card-body">

                                <form @submit.prevent="submit">

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="fw-bold">Full Name</label>
                                                <input class="form-control" v-model="form.name" :class="{ 'is-invalid': errors.name }" type="text" placeholder="Full Name">
                                                <small v-if="errors.name" class="text-danger">
                                                    {{ errors.name }}
                                                </small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="fw-bold">Email Address</label>
                                                <input class="form-control" v-model="form.email" :class="{ 'is-invalid': errors.email }" type="email" placeholder="Email Address">
                                                    <small v-if="errors.email" class="text-danger">
                                                        {{ errors.email }}
                                                    </small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="fw-bold">Password</label>
                                                <input class="form-control" v-model="form.password" :class="{ 'is-invalid': errors.password }" type="password" placeholder="Password">
                                                <small v-if="errors.password" class="text-danger">
                                                    {{ errors.password }}
                                                </small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="fw-bold">Password Confirmation</label>
                                                <input class="form-control" v-model="form.password_confirmation" type="password" placeholder="Password Confirmation">
                                                <small v-if="errors.password_confirmation" class="text-danger">
                                                    {{ errors.password_confirmation }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group mb-3">
                                                <label class="fw-bold">Roles</label>
                                                <br>
                                                <div class="form-check form-check-inline" v-for="(role, index) in roles" :key="index">
                                                    <input class="form-check-input" type="radio" v-model="form.roles" :value="role.name" :id="`check-${role.id}`">
                                                    <label class="form-check-label" :for="`check-${role.id}`">{{ role.name }}</label>
                                                </div>
                                                <br>
                                                <small v-if="errors.roles" class="text-danger">
                                                    {{ errors.roles }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <button class="btn btn-primary shadow-sm rounded-sm" type="submit">SAVE</button>
                                            <button class="btn btn-warning shadow-sm rounded-sm ms-3" type="reset">RESET</button>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>

<script>
    //import layout
    import LayoutApp from '../../../Layouts/App.vue';

    //import Heade and useForm from Inertia
    import { Head, Link } from '@inertiajs/inertia-vue3';

    //import reactive from vue
    import { reactive } from 'vue';

    //import inerita adapter
    import { Inertia } from '@inertiajs/inertia';

    export default {
        //layout
        layout: LayoutApp,

        //register component
        components: {
            Head,
            Link
        },

        //props
        props: {
            errors: Object,
            roles: Array,
        },

        //composition API
        setup() {

            //define form with reactive
            const form = reactive({
                name: '',
                email: '',
                password: '',
                password_confirmation: '',
                roles: []
            });

            //method "submit"
            const submit = () => {

                //send data to server
                Inertia.post('/apps/users', {
                    //data
                    name: form.name,
                    email: form.email,
                    password: form.password,
                    password_confirmation: form.password_confirmation,
                    roles: form.roles
                });

            }

            return {
                form,
                submit,
            };

        }
    }
</script>

<style>

</style>
