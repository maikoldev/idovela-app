<template>
    <v-carousel :hide-delimiters="hideDelimiters" :show-arrows="showArrows" :height="carouselHeight">
        <template v-slot:prev="{ on, attrs }">
            <v-btn small color="#000000" v-bind="attrs" v-on="on">
                <i class="las la-angle-left"></i>
            </v-btn>
        </template>
        <template v-slot:next="{ on, attrs }">
            <v-btn small color="#000000" v-bind="attrs" v-on="on">
                <i class="las la-angle-right"></i>
            </v-btn>
        </template>
        <template v-if="slides.length > 0">
            <v-carousel-item v-for="(slide, i) in slides" :key="i">
                <img :src="slide.src" class="carousel-item-img" />
                <div class="carousel-body white--text">
                    <h4 class="carousel-title text-uppercase mb-3">{{ title }}</h4>
                    <p class="carousel-description mb-5">{{ description }}</p>
                    <div class="carousel-hashtags">
                        <span v-for="(hashtag, i) in hashtags" :key="i" class="hashtag">
                            # {{ hashtag }}
                            <span v-if="i < hashtags.length - 1">•</span>
                        </span>
                    </div>
                </div>
            </v-carousel-item>
        </template>
        <v-carousel-item v-else>
            <img :src="itemPlaceholderUrl" class="carousel-item-img placeholder" />
        </v-carousel-item>
    </v-carousel>
</template>

<script>
export default {
    name: "CarouselDescription",
    props: {
        showArrows: {
            type: Boolean,
            default: true
        },
        hideDelimiters: {
            type: Boolean,
            default: false
        },
        slides: {
            type: Array,
            default: () => []
        },
        type: {
            type: String,
            default: "single"
        },
        title: {
            type: String,
            default: "H4"
        },
        description: {
            type: String,
            default:
                "Incluye Lorem Ipsum is simply dummy text of the printing • Lorem Ipsum has been the industry's • Incluye Lorem Ipsum is simply dummy text of the printing • Lorem Ipsum has"
        },
        hashtags: {
            type: Array,
            default: () => ["Instalaciones", "Mantenimiento", "Planes"]
        }
    },
    data() {
        return {
            itemPlaceholderUrl: "/public/assets/img/carousel-item-placeholder.png"
        };
    },
    computed: {
        carouselHeight() {
            switch (this.$vuetify.breakpoint.name) {
                case "xs":
                    return "570";
                case "sm":
                    return "440";
                case "md":
                    return "440";
                case "lg":
                    return "850";
                case "xl":
                    return "850";
            }
        }
    }
};
</script>

<style lang="scss" scoped>
.v-carousel {
    border-radius: 10px;
    background: #dfdfdf;

    &::v-deep {
        .v-window__next,
        .v-window__prev {
            top: calc(100% - 52px);
            z-index: 2;
            background: transparent;
            border-radius: 0;

            .v-btn {
                font-size: 20px;
                height: 38px;
                min-width: 38px;
                padding: 0px;
                border-radius: 5px;
                opacity: 0.5;
            }
        }

        .v-carousel__item {
            &::before {
                content: "";
                background: linear-gradient(0deg, #000000 0%, #00000000 90%, #00000000 100%);
                height: 100%;
                width: 100%;
                opacity: 50%;
                position: absolute;
            }

            img {
                max-width: 100%;
                width: 100%;
                height: 100%;
                object-fit: cover;
                object-position: center;

                &.placeholder {
                    object-fit: contain;
                }
            }
        }

        .v-carousel__controls {
            background: transparent;

            .v-item-group {
                width: 50%;
                display: flex;
                justify-content: space-around;
            }

            .v-carousel__controls__item {
                height: 8px;
                width: 8px;
                color: #000000;

                &.v-item--active {
                    color: #18191a;

                    &:before {
                        opacity: 1;
                    }
                }

                &:before {
                    opacity: 0.1;
                }
            }
        }
    }
}

.carousel {
    &-body {
        position: absolute;
        bottom: 5rem;
        padding: 1rem 1.5rem;
    }

    &-title {
        font-size: 24px;
        line-height: 30px;
        font-weight: 400;
        letter-spacing: 0.25px;
        text-transform: uppercase;

        @media (min-width: 600px) {
            font-size: 34px;
            line-height: 43px;
        }
    }

    &-description {
        font-size: 12px;
        line-height: 18px;
        font-weight: 400;
        font-family: "Roboto";
        letter-spacing: 0.5px;

        @media (min-width: 600px) {
            font-size: 15px;
        }
    }

    &-hashtags {
        font-size: 12px;
        line-height: 16px;
        font-weight: 600;
        letter-spacing: 1.5px;
        text-transform: uppercase;
    }
}
</style>
