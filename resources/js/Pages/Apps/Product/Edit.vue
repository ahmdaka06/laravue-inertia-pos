<template>
    <Head>
        <title>Edit Product - Aplikasi Kasir</title>
    </Head>
    <main class="c-main">
        <div class="container-fluid">
            <div class="fade-in">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card border-0 rounded-3 shadow border-top-purple">
                            <div class="card-header">
                                <span class="font-weight-bold"><i class="fa fa-shopping-bag"></i> EDIT PRODUCT {{ product.title }}</span>
                            </div>
                            <div class="card-body">

                                <form @submit.prevent="submit">
                                    <div class="form-group mb-3">
                                        <center>
                                            <img :src="form.urlImage" alt="" style="height: 250px;">
                                        </center>
                                        <br>
                                        <label for="">Image</label>
                                        <input class="form-control" @input="form.image = $event.target.files[0]" :class="{ 'is-invalid': errors.image }" type="file">
                                        <small v-if="errors.image" class="text-danger">
                                            {{ errors.image }}
                                        </small>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <div class="mb-3">
                                                <label class="fw-bold">Barcode</label>
                                                <input class="form-control" v-model="form.barcode" :class="{ 'is-invalid': errors.barcode }" type="text" placeholder="Barcode / Code Product">
                                                <small v-if="errors.barcode" class="text-danger">
                                                    {{ errors.barcode }}
                                                </small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="fw-bold">Category</label>
                                                <select class="form-select" :class="{ 'is-invalid': errors.category_id }" v-model="form.category_id">
                                                    <option value="">Pilih kategori...</option>
                                                    <option v-for="(category, index) in categories" :key="index" :value="category.id">{{ category.name }}</option>
                                                </select>
                                                <small v-if="errors.category_id" class="text-danger">
                                                    {{ errors.category_id }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="fw-bold">Title Product</label>
                                                <input class="form-control" v-model="form.title" :class="{ 'is-invalid': errors.title }" type="text" placeholder="Title Product">
                                            </div>
                                            <div v-if="errors.title" class="alert alert-danger">
                                                {{ errors.title }}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="fw-bold">Stock</label>
                                                <input class="form-control" v-model="form.stock" :class="{ 'is-invalid': errors.stock }" type="number" placeholder="Stock">
                                                <small v-if="errors.stock" class="text-danger">
                                                    {{ errors.stock }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="fw-bold">Description</label>
                                        <textarea class="form-control" v-model="form.description" :class="{ 'is-invalid': errors.description }" type="text" rows="4" placeholder="Description"></textarea>
                                        <small v-if="errors.description" class="text-danger">
                                            {{ errors.description }}
                                        </small>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="fw-bold">Buy Price</label>
                                                <input class="form-control" v-model="form.buy_price" :class="{ 'is-invalid': errors.buy_price }" type="number" placeholder="Buy Price">
                                                <small v-if="errors.buy_price" class="text-danger">
                                                    {{ errors.buy_price }}
                                                </small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="fw-bold">Sell Price</label>
                                                <input class="form-control" v-model="form.sell_price" :class="{ 'is-invalid': errors.sell_price }" type="number" placeholder="Sell Price">
                                                <small v-if="errors.sell_price" class="text-danger">
                                                    {{ errors.sell_price }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <button class="btn btn-primary shadow-sm rounded-sm" type="submit">UPDATE</button>
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

    //import Heade and Link from Inertia
    import { Head, Link } from '@inertiajs/inertia-vue3';

    //import reactive from vue
    import { reactive } from 'vue';

    //import inerita adapter
    import { Inertia } from '@inertiajs/inertia';

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
            errors: Object,
            categories: Array,
            product: Object
        },

        //composition API
        setup(props) {

            //define form with reactive
            const form = reactive({
                image: '',
                barcode: props.product.barcode,
                category_id: props.product.category_id,
                title: props.product.title,
                description: props.product.description,
                buy_price: props.product.buy_price,
                sell_price: props.product.sell_price,
                stock: props.product.stock,
                urlImage: props.product.image
            });

            //method "submit"
            const submit = () => {

                //send data to server
                Inertia.post(`/apps/products/${props.product.id}`, {
                    //data
                    _method: 'PUT',
                    image: form.image,
                    barcode: form.barcode,
                    category_id: form.category_id,
                    title: form.title,
                    description: form.description,
                    buy_price: form.buy_price,
                    sell_price: form.sell_price,
                    stock: form.stock
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
