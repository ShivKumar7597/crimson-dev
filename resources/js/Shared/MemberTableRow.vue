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
<td class="border-t px-6 py-4" v-if="columns.includes('uuid')">
            <textarea
                class="form-input resize border rounded-none bg-gray-100 focus:outline-none focus:shadow-outline"
                v-model="form.uuid"
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
        <td class="border-t px-6 py-4" v-if="columns.includes('day_cost')">
            <text-input
                v-model="form.day_cost"
                :error="formErrors.weight"
                class="mt-1 block w-auto"
            />
        </td>

        <td class="border-t px-6 py-4" v-if="columns.includes('hour_cost')">
            <text-input
                v-model="form.hour_cost"
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

           <td class="border-t px-6 py-4" v-if="columns.includes('flat_rate_cost')">
            <text-input
                v-model="form.flat_rate_cost"
                :error="formErrors.weight"
                class="mt-1 block w-auto"
            />
        </td>

              <td class="border-t px-6 py-4" v-if="columns.includes('membership_type')">
            <text-input
                v-model="form.membership_type"
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
                name: this.product.name,
                uuid: this.product.uuid,
                description: this.product.description,
                day_cost: this.product.day_cost,
                hour_cost: this.product.hour_cost,
                distance_cost: this.product.distance_cost,
                flat_rate_cost: this.product.flat_rate_cost,
                membership_type: this.product.membership_type,
                
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
