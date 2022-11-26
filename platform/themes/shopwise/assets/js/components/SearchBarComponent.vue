<template>
    <v-autocomplete :items="items" v-model="item" :get-label="getLabel"
                    :component-item='template' @update-items="updateItems" @focus="updateItems"
                    :auto-select-one-item="false" :input-attrs="{
                        placeholder: 'Search...',
                        class: 'form-control'
                    }" :min-len="2">
    </v-autocomplete>
</template>

<script>
import ItemTemplate from './SearchResultItemComponent.vue'
export default {
    props: {
        url: { required: true },
    },
    data () {
        return {
            item: {
                id: null,
                name: null,
                image: null
            },
            items: [],
            template: ItemTemplate,
            isLoading: false
        }
    },
    methods: {
        getLabel (item) {
            return item ? item.name : '';
        },
        updateItems (text) {
            text = text || 'recommended';
            this.isLoading = true;
            axios
                .get(`${this.url}/${text}`)
                .then((res) => {
                    console.log(res);
                    this.items = res.data.data;
                    this.isLoading = false;
                })
                .catch((res) => {
                    this.isLoading = false;
                    console.log(res);
                });
        }
    }
}
</script>
<style>
.v-autocomplete-list {
    z-index: 11;
    background: #111;
    padding: 0;
    width: 100%;
    border-radius: .5rem;
    border: 1px solid #151515;
    box-shadow: 0 6px 6px #00000040;
}
.v-autocomplete{position:relative}
.v-autocomplete .v-autocomplete-list{position:absolute}
.v-autocomplete .v-autocomplete-list .v-autocomplete-list-item{cursor:pointer}
.v-autocomplete .v-autocomplete-list .v-autocomplete-list-item.v-autocomplete-item-active{background-color:transparent!important}
.v-autocomplete-list-item {
    padding: .8rem 1.4rem;
}
@media only screen and (max-width: 991px) {
    .v-autocomplete .v-autocomplete-list {
        position: absolute;
        left: -40px;
        right: -80px;
        width: auto;
        max-width: 600px;
    }
}

.v-autocomplete-input-group span {
    position: absolute;
    z-index: 11;
    top: 18px;
    right: 1.5rem;
}
.found--item a {
    display: flex;
    align-items: center;
    color: #fff;
}

.found--item img {
    width: 50px;
    height: 50px;
    border-radius: .3rem;
    margin-right: 1rem;
    object-fit: cover;
}

.found--item span {
    display: block;
    font-weight: 600;
}

.found--item .price {
    margin-left: auto;
}
</style>
