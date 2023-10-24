<template>

    <Head>
        <title>Add New Category - Aplikasi Kasir</title>
    </Head>
    <main class="c-main">
        <div class="container-fluid">
            <div class="fade-in">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card border-0 rounded-3 shadow border-top-purple">
                            <div class="card-header">
                                <span class="font-weight-bold"><i class="fa fa-folder"></i> ADD NEW CATEGORY</span>
                            </div>
                            <div class="card-body">

                                <form @submit.prevent="submit">

                                    <div class="form-group mb-3">
                                        <label class="fw-bold">Image</label>
                                        <input class="form-control" @input="form.image = $event.target.files[0]" :class="{ 'is-invalid': errors.image }" type="file">

                                        <small v-if="errors.image" class="text-danger">
                                            {{ errors.image }}
                                        </small>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label class="fw-bold">Category Name</label>
                                        <input class="form-control" v-model="form.name" :class="{ 'is-invalid': errors.name }" type="text" placeholder="Category Name">

                                        <small v-if="errors.name" class="text-danger">
                                            {{ errors.name }}
                                        </small>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label class="fw-bold">Description</label>
                                        <textarea class="form-control" v-model="form.description" :class="{ 'is-invalid': errors.description }" type="text" rows="4" placeholder="Description"></textarea>

                                        <small v-if="errors.description" class="text-danger">
                                            {{ errors.description }}
                                        </small>
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
    //import layout App
    import LayoutApp from '../../../Layouts/App.vue';

    //import Heade and Link from Inertia
    import { Head, Link } from '@inertiajs/inertia-vue3';

    //import reactive from vue
    import { reactive } from 'vue';

    //import inerita adapter
    import { Inertia } from '@inertiajs/inertia';

    //import sweet alert2
    import Swal from 'sweetalert2';

    export default {
        //layout
        layout: LayoutApp,

        //register components
        components: {
            Head,
            Link
        },

        //props
        props: {
            errors: Object
        },

        //composition API
        setup() {

            //define form with reactive
            const form = reactive({
                name: '',
                image: '',
                description: ''
            });

            //method "submit"
            const submit = () => {

                //send data to server
                Inertia.post('/apps/categories', {
                    //data
                    name: form.name,
                    image: form.image,
                    description: form.description
                });

            }

            return {
                form,
                submit,
            }

        }
    }
</script>
<style>

</style>
