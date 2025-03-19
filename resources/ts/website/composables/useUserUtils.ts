import { ref, computed } from "vue";
export const useUserUtils = () => {
    const profileImage = ref("/api/placeholder/100/100");

    const getAvatar = (name: string | undefined) => {
        let avatar = "";

        if (name) {
            const sigla = name.split(" ");
            avatar = `${sigla[0][0]}${sigla[sigla.length - 1][0]}`;
        }

        return avatar;
    };

    return {
        profileImage,
        getAvatar,
    };
};
