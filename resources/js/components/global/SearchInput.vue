<template>
    <div class="search-input">
        <input
            @keyup.enter="search()"
            class="search-input-input d-none d-sm-block"
            :placeholder="placeholder"
            v-model="searchKeyword"
            type="search"
            required
        />
        <button class="search-input-button" type="button" @click.stop.prevent="search()">
            <SearchIcon />
            <span class="ml-2 d-none d-sm-block">
                {{ buttonLabel }}
            </span>
        </button>
    </div>
</template>

<script>
import SearchIcon from "../icons/Search.vue";

export default {
    name: "SearchInput",
    props: {
        placeholder: {
            type: String,
            default: "Escribe para buscar"
        },
        buttonLabel: {
            type: String,
            default: "Buscar"
        }
    },
    components: {
        SearchIcon
    },
    data() {
        return {
            searchKeyword: ""
        };
    },
    methods: {
        search() {
            this.$router
                .push({
                    name: "Search",
                    params: this.searchKeyword.length > 0 ? { keyword: this.searchKeyword } : {},
                    query: {
                        page: 1
                    }
                })
                .catch(() => {});
        }
    }
};
</script>

<style lang="scss" scoped>
.v-application {
    &.theme--light {
        .search-input {
            &:hover {
                .search-input-input {
                    border-color: #000000;
                }

                .search-input-button {
                    background-color: #000000;
                    border-color: #000000;

                    &::v-deep {
                        svg {
                            path {
                                fill: #ffffff;
                            }
                        }
                    }

                    span {
                        color: #ffffff;
                    }
                }
            }
        }
    }

    &.theme--dark {
        .search-input {
            &:hover {
                .search-input-button {
                    background-color: #ffffff;
                }
            }

            &-input {
                color: #ffffff;

                &::placeholder {
                    color: rgba(#ffffff, 0.5);
                }
            }
        }
    }
}

.search-input {
    display: flex;
    flex: 1;
    max-width: 836px;
    justify-content: flex-end;

    &-input {
        font-family: "Roboto";
        font-size: 15px;
        letter-spacing: 0.5px;

        border: 1px solid #dfdfdf;
        border-top-left-radius: 5px;
        border-bottom-left-radius: 5px;
        outline: none;

        width: 100%;
        padding: 0.5rem 1rem;

        transition: all 0.2s ease-in-out;

        &::placeholder {
            color: rgba(#000000, 0.5);
        }
    }

    &-button {
        display: flex;
        align-items: center;

        background-color: #dfdfdf;

        border: 1px solid #dfdfdf;
        border-radius: 5px;

        padding: 0 10px;
        height: 40px;

        transition: all 0.2s ease-in-out;

        @media (min-width: 600px) {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }

        @media (min-width: 960px) {
            padding: 0.5rem 2rem;
        }

        &::v-deep {
            svg {
                path {
                    fill: #040405;
                }
            }
        }

        span {
            color: #000000;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            line-height: 1;
            margin-top: 3px;

            @media (min-width: 960px) {
                font-size: 14px;
            }
        }
    }
}
</style>
