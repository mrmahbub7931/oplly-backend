<template>
    <div v-if="item.id" class="found--item">
        <a :href="item.url">
            <img :src="item.image" :alt="item.name">
            <div>
                <span>{{ item.name }}</span>
                <small>{{ item.title }}</small>
            </div>
            <div class="price text-white font-weight-bold">
                {{ item.formatted_price }}
            </div>
        </a>
    </div>
</template>

<script>
export default {
    props: {
        item: { required: true },
        searchText: { required: true }
    },
    computed: {
        getImgage() {
            return item.image ?? '';
        }
    }
}
</script>
