<template>
    <div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="mb-3">
                            <label for="model">
                                Model
                                <span class="text-danger fw-bold">*</span>
                            </label>
                            <input type="text"
                                   id="model"
                                   class="form-control"
                                   required
                                   placeholder="Enter Resource Name"
                                   v-model="crud.model">
                            <small class="text-muted">
                                For nested resources use '/' separator.
                            </small>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="mb-3">
                            <label for="table">
                                Custom Table
                            </label>
                            <input type="text"
                                   id="table"
                                   class="form-control"
                                   required
                                   placeholder="Enter Table Name"
                                   v-model="crud.table">
                            <small class="text-muted">
                                (Optional) empty value will use model name plural form.
                            </small>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="mb-3">
                            <label for="arch">
                                Preferred Design Pattern
                            </label>
                            <select class="form-control form-select"
                                    id="arch"
                                    v-model="crud.arch">
                                <option v-for="(arch, index) in architectures"
                                        :key="index"
                                        :selected="crud.arch === arch.value"
                                        :value="arch.value">
                                    {{ arch.label }}
                                </option>
                            </select>
                            <small class="text-muted">
                                For multiple data source repository arc is suggested.
                            </small>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="mb-3">
                            <label for="per_page">
                                Per Page Entries
                            </label>
                            <select class="form-control form-select"
                                    id="per_page"
                                    v-model="crud.per_page">
                                <option v-for="(length, index) in pageLengths"
                                        :key="index"
                                        :selected="crud.per_page === length"
                                        :value="length">
                                    {{ length === -1 ? 'All' : length }}
                                </option>
                            </select>
                            <small class="text-muted">
                            </small>
                        </div>
                    </div>
                </div>
                <!-- Route Actions -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="options" class="d-block mb-1">
                                Actions
                            </label>
                            <div class="form-check form-check-inline" v-for="(flag, option) in crud.actions"
                                 :key="option">
                                <input class="form-check-input"
                                       type="checkbox"
                                       v-model="crud.actions[option]"
                                       :id="'model-action-' + option"
                                       :checked="crud.actions[option]"
                                       :value="flag">
                                <label class="form-check-label" :for="'model-action-' + option">
                                    {{ displayOption(option) }}
                                </label>
                            </div>
                            <small class="text-muted">
                            </small>
                        </div>
                    </div>
                </div>
                <!-- Model Options -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="options" class="d-block mb-1">
                                Options
                            </label>
                            <div class="form-check form-check-inline" v-for="(flag, option) in crud.options"
                                 :key="option">
                                <input class="form-check-input"
                                       type="checkbox"
                                       v-model="crud.options[option]"
                                       :id="'model-option-' + option"
                                       :checked="crud.options[option]"
                                       :value="flag">
                                <label class="form-check-label" :for="'model-option-' + option">
                                    {{ displayOption(option) }}
                                </label>
                            </div>
                            <small class="text-muted">
                            </small>
                        </div>
                    </div>
                </div>
                <!-- Fields -->
                <div class="row">
                    <div class="col-md-12">
                        <label for="fields" class="mb-1">Fields</label>
                        <div class="bg-light border rounded p-3">
                            <draggable
                                item-key="uuid"
                                :list="crud.fields"
                                tag="div"
                                handle=".accordion-header"
                                class="accordion"
                                id="fieldAccordion">
                                <template #item="{element, index}">
                                    <field :field="element"
                                           :index="index"
                                           :key="index"/>
                                </template>
                            </draggable>
                            <button type="button"
                                    class="btn btn-sm w-100 d-block btn-outline-primary mt-2"
                                    @click="addMoreField">
                                + Add More Field
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button
                    class="btn btn-success d-block"
                    @click="generateStubs">
                    Generate Stubs
                </button>
            </div>
        </div>
        <div class="toast-container position-fixed bottom-0 end-0 p-3">
            <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <img src="#" class="rounded me-2" alt="...">
                    <strong class="me-auto">Bootstrap</strong>
                    <small>11 mins ago</small>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    Hello, world! This is a toast message.
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Field from "./Field.vue";
import draggable from 'vuedraggable';
import {v4 as uuidv4} from 'uuid';
import axios from "axios";
import * as bootstrap from 'bootstrap';
const { Toast } = bootstrap

export default {
    name: "App",
    components: {Field, draggable},
    data() {
        return {
            errors: {},
            architectures: [
                {
                    label: 'Model View Controller',
                    value: 'mvc'
                },
                {
                    label: 'Repository Design Pattern',
                    value: 'rdp'
                },
            ],
            pageLengths: [10, 20, 50, 100, 250, 500, -1],
            crud: {
                model: '',
                table: '',
                arch: 'mvc',
                per_page: 20,
                options: {
                    soft_delete: true,
                    timestamps: true,
                    migration: true,
                    factory: false,
                    audit: false,
                    request: true,
                },
                actions: {
                    index: true,
                    create: true,
                    store: true,
                    edit: true,
                    update: true,
                    show: true,
                    destroy: true,
                    import: false,
                    export: false,
                },
                fields: []
            }
        }
    },
    created() {
        this.initDefaultSettings();
    },
    methods: {
        initDefaultSettings() {
            this.addMoreField({
                name: 'id',
                label: 'ID',
                type: 'id',
                input_type: 'number',
                display_in_create: false,
                display_in_update: false
            });
        },

        displayOption(key) {
            return key.toString()
                .replace(/_/g, ' ')
                .split(' ')
                .map((word) => {
                    return word[0].toUpperCase() + word.substring(1);
                })
                .join(" ");

        },

        addMoreField(attributes = {}) {
            for (const fieldsKey in this.crud.fields) {
                this.crud.fields[fieldsKey].collapsed = true;
            }
            this.crud.fields.push({
                uuid: uuidv4(),
                readonly: attributes.readonly ?? false,
                collapsed: attributes.collapsed ?? false,
                name: attributes.name ?? '',
                label: attributes.label ?? '',
                type: attributes.type ?? 'string',
                input_type: attributes.input_type ?? 'text',
                required: attributes.required ?? false,
                validation: attributes.required ?? null,
                display_in_list: attributes.display_in_list ?? true,
                display_in_create: attributes.display_in_create ?? true,
                display_in_update: attributes.display_in_update ?? true,
                display_in_detail: attributes.display_in_detail ?? true,
            });
        },

        removeField(index) {
            this.crud.fields.splice(index, 1);
        },

        removeFieldByName(name) {
            let elementIndex = null;

            this.crud.fields.forEach((field, index) => {
                if (field.name === 'deleted_at') {
                    elementIndex = index;
                }
            });

            if (elementIndex) {
                this.removeField(elementIndex);
            }
        },

        generateStubs() {
            axios
                .post('/crud/generate', this.crud, {
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    }
                })
                .then(response => {
                    console.log(response.data);
                }).catch(error => {
                this.errors = error.toJSON();
                let toast = new Toast(document.getElementById('liveToast'));
                toast.show();
                //console.error(error);
            });
        }
    },
    watch: {
        "crud.options.soft_delete": function (newValue, oldValue) {
            (newValue)
                ? this.addMoreField({
                    readonly: true,
                    name: 'deleted_at',
                    label: 'Deleted At',
                    type: 'softDeletes',
                    input_type: 'datetime'
                })
                : this.removeFieldByName('deleted_at');
        },
        "crud.options.timestamps": function (newValue, oldValue) {
            (newValue)
                ? this.addMoreField({
                    readonly: true,
                    name: 'created_at',
                    label: 'Created & Updated At',
                    type: 'timestamps',
                    input_type: 'datetime'
                })
                : this.removeFieldByName('created_at');
        },

        "crud.options.migration": function (newValue, oldValue) {
            (newValue)
                ? this.addMoreField({
                    readonly: true,
                    name: 'deleted_at',
                    label: 'Deleted At',
                    type: 'softDeletes',
                    input_type: 'datetime'
                })
                : this.removeFieldByName('deleted_at');
        }
    }
}
</script>

<style scoped>

</style>
