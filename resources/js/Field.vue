<template>
    <div class="accordion-item">
        <div class="accordion-header" :id="'heading-' + index">
            <div class="accordion-button d-flex gap-2 p-2"
                 type="button"
                 data-bs-toggle="collapse"
                 :data-bs-target="'#collapse-' + index"
                 :aria-expanded="field.collapsed"
                 :aria-controls="'collapse-' + index">
                <p class="mb-0 ml-3">#{{ index + 1 }} Field: <span class="fw-bold">{{ field.label || 'N/A' }}</span>
                    Type: <span class="fw-bold">{{ field.input_type || 'N/A' }}</span>
                    Required: <span class="fw-bold">
                    {{ (field.required || false) ? 'Yes' : 'No' }}
                </span>
                </p>
            </div>
        </div>
        <div :id="'collapse-' + index"
             class="accordion-collapse collapse"
             :class="{'show' : !field.collapsed, 'fade' : field.collapsed}"
             :aria-labelledby="'heading-' + index"
             data-bs-parent="#fieldAccordion">
            <div class="accordion-body">
                <button class="btn btn-sm rounded-circle btn-outline-secondary"
                        @click="$parent.removeField(index);"
                        style="width: 30px; height: 30px">x
                </button>
                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label :for="'field-' + index + '-label'">
                                Label
                                <span class="text-danger fw-bold">*</span>
                            </label>
                            <input type="text"
                                   :disabled="field.readonly"
                                   :id="'field-' + index + '-label'"
                                   class="form-control"
                                   placeholder="Enter Field Label"
                                   v-model="field.label">
                            <small class="text-muted">
                                Write Human readable text.
                            </small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label :for="'field-' + index + '-name'">
                                Name
                                <span class="text-danger fw-bold">*</span>
                            </label>
                            <input type="text"
                                   :disabled="field.readonly"
                                   :id="'field-' + index + '-name'"
                                   class="form-control"
                                   required
                                   placeholder="Enter Field Name"
                                   v-model="field.name">
                            <small class="text-muted">
                                Write <em>snack_case</em> field name
                            </small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label :for="'field-' + index + '-type'">
                                DB Column Type
                                <span class="text-danger fw-bold">*</span>
                            </label>
                            <select :id="'field-' + index + '-type'"
                                    class="form-control form-select"
                                    required
                                    :disabled="field.readonly"
                                    v-model="field.type">
                                <option value="" selected>Select an Colum Type</option>
                                <option v-for="(column, type) in columnTypes"
                                        :key="type"
                                        :value="type">
                                    {{ column.label }}
                                </option>
                            </select>
                            <small class="text-muted">
                                Relation Handle Under development
                            </small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label :for="'field-' + index + '-input-type'">
                                HTML Input Type
                            </label>
                            <input type="text"
                                   :disabled="noInputFields.includes(field.type)"
                                   :id="'field-' + index + '-input-type'"
                                   class="form-control"
                                   placeholder="Enter Column Type"
                                   v-model="field.input_type">
                            <small class="text-muted">
                                Relation Handle Under development
                            </small>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label :for="'field-' + index + '-validation'">
                                Validation
                            </label>
                            <textarea
                                :id="'field-' + index + '-validation'"
                                class="form-control"
                                required
                                placeholder="Enter Validation Rules"
                                v-model="field.validation">{{ field.validation }}</textarea>
                            <small class="text-muted">
                                Split the rules using '|' symbol.
                            </small>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label class="d-block mb-2">Field Options</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input"
                                   type="checkbox"
                                   :id="'field-'+ index + 'required'"
                                   v-model="field.required">
                            <label class="form-check-label"
                                   :for="'field-'+ index + 'required'">Is Required Field</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input"
                                   type="checkbox"
                                   :id="'field-'+ index + 'display_in_list'"
                                   v-model="field.display_in_list">
                            <label class="form-check-label"
                                   :for="'field-'+ index + 'display_in_list'">Display in List</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input"
                                   type="checkbox"
                                   :id="'field-'+ index + 'display_in_create'"
                                   v-model="field.display_in_create">
                            <label class="form-check-label"
                                   :for="'field-'+ index + 'display_in_create'">Display in Create Form</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input"
                                   type="checkbox"
                                   :id="'field-'+ index + 'display_in_update'"
                                   v-model="field.display_in_update">
                            <label class="form-check-label"
                                   :for="'field-'+ index + 'display_in_update'">Display in Update Form</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input"
                                   type="checkbox"
                                   :id="'field-'+ index + 'display_in_detail'"
                                   v-model="field.display_in_detail">
                            <label class="form-check-label"
                                   :for="'field-'+ index + 'display_in_detail'">Display in Detail Screen</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
export default {
    name: "Field",
    props: {
        field: {
            type: Object,
            default: {}
        },
        index: {
            type: Number,
            required: true
        }
    },
    data() {
        return {
            noInputFields: ['id', 'softDeletes'],
            columnTypes: {
                id: {
                    label: 'Primary Key',
                    inputType: 'number',
                },
                bigInteger: {
                    label: 'Big Integer',
                    inputType: 'number',
                },
                softDeletes: {
                    label: 'Soft Deleted',
                    inputType: 'datetime',
                },
                string: {
                    label: 'String',
                    inputType: 'text',
                },
                text: {
                    label: 'Text',
                    inputType: 'textarea',
                },
                longText: {
                    label: 'Long Text',
                    inputType: 'textarea',
                },
                timestamps: {
                    label: 'Create & Update Timestamps',
                    inputType: 'datetime',
                },
                // bigInteger: {
                //     label: 'Big Integer',
                //     inputType: 'number',
                // },
                // bigInteger: {
                //     label: 'Big Integer',
                //     inputType: 'number',
                // },
                // bigInteger: {
                //     label: 'Big Integer',
                //     inputType: 'number',
                // },
                // bigInteger: {
                //     label: 'Big Integer',
                //     inputType: 'number',
                // },
                // bigInteger: {
                //     label: 'Big Integer',
                //     inputType: 'number',
                // },
                // bigInteger: {
                //     label: 'Big Integer',
                //     inputType: 'number',
                // },
                // bigInteger: {
                //     label: 'Big Integer',
                //     inputType: 'number',
                // },
                // bigInteger: {
                //     label: 'Big Integer',
                //     inputType: 'number',
                // },
                // bigInteger: {
                //     label: 'Big Integer',
                //     inputType: 'number',
                // },
                // bigInteger: {
                //     label: 'Big Integer',
                //     inputType: 'number',
                // },
                // bigInteger: {
                //     label: 'Big Integer',
                //     inputType: 'number',
                // },
                // bigInteger: {
                //     label: 'Big Integer',
                //     inputType: 'number',
                // },
                // bigInteger: {
                //     label: 'Big Integer',
                //     inputType: 'number',
                // },

            },
        };
    },
    methods: {
        slugifyLabelForName(event) {
            this.field.name = event.target.value.toString()
                .replace(/\s+/g, '_').toLowerCase();
        }
    }
}
</script>

<style scoped>

</style>
