<template>
    <div class="user-nav">
        <v-divider class="d-sm-none d-lg-block my-3 my-lg-5" />

        <h6 class="user-nav-label mb-3">Compras</h6>
        <div class="user-nav-buttons top mb-5 mb-sm-0">
            <template v-for="item in userNavLinks.purchase">
                <CustomButton
                    :key="`UserNav${item.link}`"
                    :to="{ name: item.link }"
                    :text="$t(item.label)"
                    color="nero2"
                    block
                />
            </template>
        </div>

        <v-divider class="d-sm-none d-lg-block my-3" />
        <v-divider class="d-none d-sm-inline-flex d-lg-none mx-5" vertical />

        <h6 class="user-nav-label mb-3">Usuario</h6>
        <div class="user-nav-buttons bottom mb-3 mb-sm-0">
            <template v-for="item in userNavLinks.user">
                <CustomButton
                    :key="`UserNav${item.link}`"
                    :to="{ name: item.link }"
                    :text="$t(item.label)"
                    color="nero2"
                    block
                />
            </template>
        </div>

        <v-divider class="d-none d-md-block my-5" />
    </div>
</template>

<script>
import { mapState } from "vuex";

import CustomButton from "../../components/global/CustomButton.vue";

export default {
    components: {
        CustomButton
    },
    data() {
        return {
            userNavLinks: {
                purchase: [
                    { label: "Carrito", link: "Cart" },
                    { label: "Favoritos", link: "Favorites" },
                    { label: "Facturas", link: "PurchaseHistory" }
                ],
                user: [
                    { label: "Perfil", link: "Profile" },
                    { label: "Notificaciones", link: "NotificationAll" }
                ]
            }
        };
    }
};
</script>

<style lang="scss" scoped>
.user-nav {
    @media (min-width: 600px) and (max-width: 1264px) {
        display: flex;
    }

    .v-divider {
        border-color: #e4e4e4 !important;
        border-width: 2px 0 0;

        &--vertical {
            border-width: 0 2px 0 0;
        }
    }

    &-label {
        font-weight: 600;

        @media (min-width: 600px) and (max-width: 1264px) {
            display: none;
        }
    }

    &-buttons {
        display: flex;
        flex-direction: column;
        gap: 0.65rem;

        @media (min-width: 600px) and (max-width: 1264px) {
            flex-direction: row;

            &.top {
                width: 60%;
            }

            &.bottom {
                width: 40%;
            }

            .v-btn {
                min-width: auto !important;
                flex: 1;
            }
        }

        @media (min-width: 960px) {
            gap: 1rem;
        }
    }
}
</style>
