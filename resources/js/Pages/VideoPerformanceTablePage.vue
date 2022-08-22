<template>
    <div id="VideoPerformanceTable">
        <div class="container divider">
            <div class="input-group mb-3">
                <input v-model="videoNameFilter" type="text" class="form-control" placeholder="Name" aria-label="Name"
                       aria-describedby="basic-addon2">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="button" @click="clearVideoNameFilter">X</button>
                </div>
                <input v-model="videoTagFilter" type="text" class="form-control" placeholder="Tag" aria-label="Tag"
                       aria-describedby="basic-addon2">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary btn-danger" type="button" @click="clearVideoTagFilter">
                        X
                    </button>
                </div>
            </div>
        </div>
        <div class="container" v-if="loaded">
            <table class="table">
                <thead class="thead-dark">
                <tr>
                    <th>Name</th>
                    <th>Channel</th>
                    <th @click="sortAsc = !sortAsc">
                        Performance
                        <span v-if="sortAsc">↑</span>
                        <span v-else>↓</span>
                    </th>
                    <th>Tags</th>
                </tr>
                </thead>
                <tbody v-if="items.length > 0">
                <tr v-for="(item) in items">
                    <td>{{ item.name }}</td>
                    <td>{{ item.channel.name }}</td>
                    <td>{{ item.performance.toFixed(2) }}</td>
                    <td>{{ item.videoTagNames }}</td>
                </tr>
                </tbody>
                <tbody v-else>
                <div class="no-data-text">No data found</div>
                </tbody>
            </table>
            <div class="pagination" v-if="items.length > 0">
                <button
                    class="btn btn-outline-secondary"
                    type="button"
                    :disabled="previousButtonDisabled"
                    @click="currentPage = currentPage - 1"
                >
                    Previous
                </button>
                <span class="current-page-number">{{ currentPage }} </span>
                <button
                    class="btn btn-outline-secondary"
                    type="button"
                    :disabled="nextPageButtonDisabled"
                    @click="currentPage = currentPage + 1">Next
                </button>
            </div>
        </div>
    </div>

</template>

<script>

export default {
    name: "VideoPerformanceTable",
    data() {
        return {
            loaded: false,
            items: [],
            videoNameFilter: '',
            videoTagFilter: '',
            sortAsc: true,
            sort: 'ASC',
            currentPage: 1,
            perPage: 10,
            lastPage: 1,
        }
    },
    created() {
        this.getData();
    },
    computed: {
        previousButtonDisabled() {
            return this.currentPage <= 1;
        },
        nextPageButtonDisabled() {
            return this.currentPage >= this.lastPage;
        },
    },
    watch: {
        sortAsc(newValue) {
            this.sort = newValue ? 'ASC' : 'DESC';

            this.getData();
        },
        videoNameFilter() {
            this.initializeInputSearch()
        },
        videoTagFilter() {
            this.initializeInputSearch()
        },
        currentPage() {
            this.getData();
        },
    },
    methods: {
        getData() {
            this.loaded = false;
            window.axios.get('/api/videos', {
                params: {
                    videoNameFilter: this.videoNameFilter,
                    videoTagFilter: this.videoTagFilter,
                    sort: this.sort,
                }
            })
                .then(data => {
                    this.loaded = true;

                    let items = [];
                    data.data.data.forEach(item => {
                        let videoTagNames = [];
                        item.video_tags.forEach(videoTag => {
                            videoTagNames.push(videoTag.name);
                        });

                        item.videoTagNames = videoTagNames.join(',')
                        items.push(item);
                    });

                    this.items = items;
                    this.lastPage = data.data.last_page;
                });
        },
        initializeInputSearch() {
            if (!this.awaitingSearch) {
                setTimeout(() => {
                    this.getData();

                    this.awaitingSearch = false;
                }, 500);
            }
            this.awaitingSearch = true;
        },
        clearVideoNameFilter() {
            this.videoNameFilter = '';
        },
        clearVideoTagFilter() {
            this.videoTagFilter = '';
        },
    },
}
</script>

<style scoped>
.divider {
    margin-top: 10px;
}

.no-data-text {
    font-size: 20px;
    font-weight: 800;
}

.current-page-number {
    font-size: 26px;
    margin-left: 10px;
    margin-right: 10px;
}
</style>
