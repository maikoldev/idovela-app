<template>
    <CustomButton :color="$vuetify.theme.dark ? 'grey' : 'black2'" class="double-button" :to="{ name: 'Cart' }">
        <span class="double-button-label mr-2 mr-sm-3 pr-2 pr-sm-3">
            {{ userShortName ? userShortName : "Iniciar Sesión" }}
        </span>
        <div class="double-button-cart">
            <ShopCartIcon class="mr-2 mr-sm-3" />
            <span class="mr-2 mr-sm-3">{{ getCartCount }}</span>
            <span class="status-indicator" :class="{ active: userShortName }"></span>
        </div>
    </CustomButton>
</template>

<script>
import { mapGetters, mapActions } from "vuex";

import CustomButton from "../global/CustomButton.vue";

import ShopCartIcon from "../icons/ShopCart.vue";

export default {
    components: {
        CustomButton,

        // Icons
        ShopCartIcon
    },
    computed: {
        ...mapGetters("cart", ["getCartCount"]),
        ...mapGetters("auth", ["userShortName"])
    },
    created() {
        this.fetchCartProducts();
    },
    methods: {
        ...mapActions("cart", ["fetchCartProducts"])
    }
};
</script>

<style lang="scss" scoped>
.double-button {
    padding: 0 10px !important;

    @media (min-width: 960px) {
        padding: 0 14px !important;
    }

    &-label {
        position: relative;
        display: block;
        letter-spacing: 0;

        &::after {
            content: "";
            display: block;
            width: 1px;
            height: 100%;
            background-color: currentColor;
            position: absolute;
            right: -1px;
            z-index: 2;
            top: 0;
            bottom: 0;
        }
    }

    &-cart {
        display: flex;
        align-items: center;

        font-family: "Roboto", sans-serif;
        font-size: 12px;
        font-weight: 400;
        letter-spacing: 0;

        @media (min-width: 960px) {
            font-size: 15px;
        }

        svg {
            width: 20px;

            @media (min-width: 960px) {
                width: 24px;
            }
        }
    }
}

.status-indicator {
    width: 5px;
    height: 5px;
    background-color: rgba(#000000, 0.35);
    display: block;
    border-radius: 50%;

    &.active {
        background-color: #00ff3a;
    }
}
</style>
