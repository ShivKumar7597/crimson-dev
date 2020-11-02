<template>
    <tr class="hover:bg-gray-100 items-center focus-within:bg-gray-100">

       <td class="border-t px-6 py-4" v-if="columns.includes('subject')">
            <textarea
                class="form-input resize border rounded-none bg-gray-100 focus:outline-none focus:shadow-outline"
                v-model="form.subject"
                rows="4"
            ></textarea>
        </td>

        <td class="border-t px-6 py-4" v-if="columns.includes('description')">
            <textarea
                class="form-input resize border rounded-none bg-gray-100 focus:outline-none focus:shadow-outline"
                v-model="form.description"
                rows="4"
            ></textarea>
        </td>


        <!-- end of select type -->
        <td class="border-t px-6 py-4" v-if="columns.includes('state_name')">
            <text-input
                v-model="form.state_name"
                :error="formErrors.weight"
                class="mt-1 block w-auto"
            />
        </td>

        <td class="border-t px-6 py-4" v-if="columns.includes('rating')">
            <text-input
                v-model="form.rating"
                :error="formErrors.weight"
                class="mt-1 block w-auto"
            />
        </td>

          <td class="border-t px-6 py-4" v-if="columns.includes('distance_cost')">
            <text-input
                v-model="form.distance_cost"
                :error="formErrors.weight"
                class="mt-1 block w-auto"
            />
        </td>

           <td class="border-t px-6 py-4" v-if="columns.includes('revenue')">
            <text-input
                v-model="form.revenue"
                :error="formErrors.weight"
                class="mt-1 block w-auto"
            />
        </td>

        <td class="border-t px-6 py-4" v-if="columns.includes('reference')">
            <text-input
                v-model="form.reference"
                :error="formErrors.weight"
                class="mt-1 block w-auto"
            />
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
                subject: this.product.subject,
                description: this.product.description,
                state_name: this.product.state_name,
                rating: this.product.rating,
                revenue: this.product.revenue,
                reference: this.product.reference,  
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
            this.$v.$touch();
            if (this.$v.$invalid) {
                return;
            }
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
