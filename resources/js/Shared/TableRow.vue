<template>
    <tr class="hover:bg-gray-100 items-center focus-within:bg-gray-100">
        <td class="border-t px-6 py-4" v-if="columns.includes('name')">
            <text-input
                v-model.trim="form.name"
                :error="formErrors.name"
                class="mt-1 block w-auto"
            />
            <div
                v-if="!$v.form.name.required"
                class="mt-2 error text-sm text-red-600"
            >
                This field is required
            </div>
        </td>

        <td class="border-t px-6 py-4" v-if="columns.includes('description')">
            <textarea
                class="form-input resize border rounded-none bg-gray-100 focus:outline-none focus:shadow-outline"
                v-model="form.description"
                rows="4"
            ></textarea>
        </td>

        <!-- select type -->
        <td class="border-t px-6 py-4" v-if="columns.includes('stock_method')">
            <select-input v-model="form.stock_method" class="mt-1 block w-auto">
                <template v-for="option in stockMethods">
                    <option :value="option.id" :key="option.id">{{
                        option.name
                    }}</option>
                </template>
            </select-input>
        </td>

        <!-- end of select type -->

        <td class="border-t px-6 py-4" v-if="columns.includes('weight')">
            <text-input
                v-model="form.weight"
                :error="formErrors.weight"
                class="mt-1 block w-auto"
            />
        </td>
        <td class="border-t px-6 py-4" v-if="columns.includes('product_group')">
            <select-input
                v-model="form.product_group_id"
                class="mt-1 block w-auto"
            >
                <option :value="null" />
                <template v-for="option in pGroups">
                    <option :value="option.id" :key="option.id">{{
                        option.name
                    }}</option>
                </template>
            </select-input>
        </td>

        <!-- select type -->
        <td
            class="border-t px-6 py-4"
            v-if="columns.includes('allowed_stock_type')"
        >
            <select-input
                v-model="form.allowed_stock_type"
                class="mt-1 block w-auto"
            >
                <option :value="null" />
                <template v-for="option in stockTypes">
                    <option :value="option.id" :key="option.id">{{
                        option.name
                    }}</option>
                </template>
            </select-input>
        </td>
        <!-- end of select type -->

        <!-- Revenue group -->
        <td class="border-t px-6 py-4" v-if="columns.includes('revenue_group')">
            <template v-if="form.allowed_stock_type != 2">
                <select-input
                    v-model="form.rental_revenue_group"
                    class="block w-auto"
                    label="Rental Revenue Group"
                >
                    <option :value="null" />
                    <template v-for="option in rGroups">
                        <option :value="option.id" :key="option.id">{{
                            option.name
                        }}</option>
                    </template>
                </select-input>
                <div
                    v-if="!$v.form.rental_revenue_group.required"
                    class="mt-2 error text-sm text-red-600"
                >
                    This field is required
                </div>
            </template>

            <!-- Sale Revenue group -->
            <template v-if="form.allowed_stock_type != 1">
                <select-input
                    v-model="form.sale_revenue_group"
                    class="block w-auto"
                    label="Sale Revenue Group"
                >
                    <option :value="null" />
                    <template v-for="option in rGroups">
                        <option :value="option.id" :key="option.id">{{
                            option.name
                        }}</option>
                    </template>
                </select-input>
                <div
                    v-if="!$v.form.sale_revenue_group.required"
                    class="mt-2 error text-sm text-red-600"
                >
                    This field is required
                </div>
            </template>

            <!-- end of sale revenue group -->
        </td>
        <!-- end of revenue type -->

        <!-- start of cost group -->
        <td class="border-t px-6 py-4" v-if="columns.includes('cost_group')">
            <template v-if="form.allowed_stock_type != 2">
                <select-input
                    v-model="form.sub_rental_cost_group"
                    class="block w-auto"
                    label="Sub Rental Cost Group"
                >
                    <option :value="null" />
                    <template v-for="option in cGroups">
                        <option :value="option.id" :key="option.id">{{
                            option.name
                        }}</option>
                    </template>
                </select-input>
                <div
                    v-if="!$v.form.sub_rental_cost_group.required"
                    class="mt-2 error text-sm text-red-600"
                >
                    This field is required
                </div>
            </template>

            <template v-if="form.allowed_stock_type != 1">
                <select-input
                    v-model="form.purchase_cost_group"
                    class="block w-auto"
                    label="Purchase Cost Group"
                >
                    <option :value="null" />
                    <template v-for="option in cGroups">
                        <option :value="option.id" :key="option.id">{{
                            option.name
                        }}</option>
                    </template>
                </select-input>
                <div
                    v-if="!$v.form.purchase_cost_group.required"
                    class="mt-2 error text-sm text-red-600"
                >
                    This field is required
                </div>
            </template>
        </td>
        <!-- end of cost group -->

        <!-- start of the price section -->
        <td class="border-t px-6 py-4" v-if="columns.includes('price')">
            <!-- start of rate definition -->
            <select-input
                v-if="form.allowed_stock_type != 2"
                v-model="form.sub_rental_rate_definition"
                class="block w-auto"
                label="Sub Rental Rate Definition"
            >
                <option :value="null" />
                <template v-for="option in srDefinitions">
                    <option :value="option.id" :key="option.id">{{
                        option.name
                    }}</option>
                </template>
            </select-input>
            <!-- end of rate definiton -->

            <template>
                <text-input
                    v-if="form.allowed_stock_type != 1"
                    v-model="form.purchase_price"
                    :error="formErrors.purchase_price"
                    class="mt-1 block w-auto"
                    label="Purchase Price"
                />
                <div
                    v-if="!$v.form.purchase_price.required"
                    class="mt-2 error text-sm text-red-600"
                >
                    This field is required
                </div>
            </template>

            <template>
                <text-input
                    v-if="form.allowed_stock_type != 2"
                    v-model="form.sub_rental_price"
                    :error="formErrors.sub_rental_price"
                    class="mt-1 block w-auto"
                    label="Sub Rental Price"
                />
                <div
                    v-if="!$v.form.sub_rental_price.required"
                    class="mt-2 error text-sm text-red-600"
                >
                    This field is required
                </div>
            </template>
        </td>
        <!-- end of the price section -->

        <td
            class="border-t px-6 py-4"
            v-if="columns.includes('replacement_charge')"
        >
            <text-input
                v-model="form.replacement_charge"
                :error="formErrors.replacement_charge"
                class="mt-1 block w-auto"
            />
        </td>
        <td class="border-t px-6 py-4" v-if="columns.includes('accessories')">
            <text-input
                v-model="form.accessories"
                :error="formErrors.accessories"
                class="mt-1 block w-auto"
            />
        </td>
        <!-- Start of alternative products section -->
        <td
            class="border-t px-6 py-4"
            v-if="columns.includes('alternative_products')"
        >
            <div
                v-for="(alternativeProduct, index) in alternativeProducts"
                :key="index"
            >
                <div class="flex items-center">
                    <input
                        type="text"
                        v-model="alternativeProduct.productId"
                        name="alternativeProducts[][productId]"
                        class="mr-1 mb-1 p-2 leading-normal block w-full border text-gray-700 bg-white font-sans rounded text-left appearance-none relative"
                    />
                    <button
                        type="button"
                        v-on:click="removeAlternativeProduct(index)"
                        class="rounded-full h-10 w-10 flex items-center justify-center bg-red-500 border border-transparent text-white hover:bg-red-600 focus:outline-none focus:border-red-700 focus:shadow-outline-red active:bg-red-600 transition ease-in-out duration-150"
                    >
                        <svg
                            class="w-6 h-6"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"
                            ></path>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="mt-2">
                <button
                    type="button"
                    v-on:click="addNewAlternativeProduct"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:shadow-outline-indigo focus:border-indigo-700 active:bg-indigo-700 transition duration-150 ease-in-out"
                >
                    <svg
                        class="-ml-1 mr-2 w-5 h-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"
                        ></path>
                    </svg>
                    Add Alternative Products
                </button>
            </div>
            <!-- <text-input
                v-model="form.alternative_products"
                :error="formErrors.alternative_products"
                class="mt-1 block w-auto"
            /> -->
        </td>
        <!-- End of alternative products section -->

        <td class="border-t w-px">
            <button
                :disabled="sending"
                type="submit"
                @click="submit"
                class="inline-flex items-center justify-center px-4 py-2 bg-red-500 border border-transparent rounded-full font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-600 focus:outline-none focus:border-red-700 focus:shadow-outline-red active:bg-red-600 transition ease-in-out duration-150"
            >
                <div v-if="sending" class="btn-spinner mr-2" />
                Save
            </button>
        </td>
    </tr>
</template>

<script>
import TextInput from "./TextInput";
import SelectInput from "./SelectInput";
import JetInputError from "../Jetstream/InputError";
import { required } from "vuelidate/lib/validators";
import Vue from "vue";

export default {
    components: {
        TextInput,
        SelectInput,
        JetInputError
    },

    data() {
        return {
            validationActive: false,
            sending: false,
            form: {
                name: this.product.name,
                description: this.product.description,
                stock_method: this.product.stock_method,
                weight: this.product.weight,
                product_group_id: this.product.product_group_id,
                purchase_price: this.product.purchase_price,
                sub_rental_price: this.product.sub_rental_price,
                allowed_stock_type: this.product.allowed_stock_type,
                replacement_charge: this.product.replacement_charge,
                accessories: this.product.accessories,
                alternative_products: this.product.alternative_products,
                rental_revenue_group: this.product.rental_revenue_group,
                sale_revenue_group: this.product.sale_revenue_group,
                sub_rental_cost_group: this.product.sub_rental_cost_group,
                sub_rental_rate_definition: this.product
                    .sub_rental_rate_definition,
                purchase_cost_group: this.product.purchase_cost_group
            },
            alternativeProduct: {
                productId: "",
                name: ""
            },
            alternativeProducts: []
        };
    },

    validations: {
        form: {
            name: {
                required
            },
            purchase_price: {
                required
            },
            sub_rental_price: {
                required
            },
            rental_revenue_group: {
                required
            },
            sale_revenue_group: {
                required
            },
            sub_rental_cost_group: {
                required
            },
            purchase_cost_group: {
                required
            }
        }
    },

    props: {
        product: Object,
        columns: Array,
        formErrors: Object,
        stockTypes: Array,
        stockMethods: Array,
        pGroups: Array,
        rGroups: Array,
        cGroups: Array,
        srDefinitions: Array
    },

    mounted: function() {
        this.alternativeProducts = [];
    },

    methods: {
        submit() {
            // this.$v.$touch();
            // if (this.$v.$invalid) {
            //     return;
            // }
            this.$inertia.put(
                this.route("products.update", this.product.id),
                this.form,
                {
                    onStart: () => (this.sending = true),
                    onFinish: () => (this.sending = false)
                }
            );
        },

        addNewAlternativeProduct: function() {
            this.alternativeProducts.push(
                Vue.util.extend({}, this.alternativeProduct)
            );
        },

        removeAlternativeProduct: function(index) {
            Vue.delete(this.alternativeProducts, index);
        }
    }
};
</script>
