import {Icon} from '@wordpress/icons';
import {__} from "@wordpress/i18n";
import settings from "./settings";

const paymentGateways = {
    name: 'custom-block-editor/payment-gateways',
    category: 'input',
    settings: {
        ...settings,
        title: __('Payment Gateways', 'custom-block-editor'),
        supports: {
            multiple: false,
        },
        attributes: {
            lock: {remove: true},
        },
        edit: () => <div style={{
            padding: '24px',
            textAlign: 'center',
            border: '1px dashed var(--give-gray-100)',
            borderRadius: '5px',
            backgroundColor: 'var(--give-gray-10)',
        }}>
            <div style={{display: 'flex', flexDirection: 'column', gap: '8px'}}>
                <GatewayItem label={__('Test Donation', 'give')} icon={
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M9.21293 14.6663H6.78626C6.63422 14.6664 6.48673 14.6144 6.36827 14.5191C6.2498 14.4238 6.16747 14.2909 6.13493 14.1423L5.8636 12.8863C5.50164 12.7277 5.15842 12.5294 4.84026 12.295L3.6156 12.685C3.47064 12.7312 3.31423 12.7265 3.17234 12.6715C3.03046 12.6166 2.91163 12.5148 2.8356 12.383L1.6196 10.2823C1.54436 10.1504 1.51612 9.99689 1.53949 9.84684C1.56287 9.69679 1.63647 9.55912 1.74826 9.45634L2.69826 8.58967C2.65506 8.19708 2.65506 7.80093 2.69826 7.40834L1.74826 6.54367C1.63631 6.44086 1.56261 6.30306 1.53923 6.15286C1.51585 6.00267 1.54419 5.84899 1.6196 5.71701L2.83293 3.61501C2.90896 3.48322 3.02779 3.3814 3.16968 3.32647C3.31156 3.27153 3.46797 3.26678 3.61293 3.31301L4.8376 3.70301C5.00026 3.58301 5.1696 3.47101 5.34426 3.36967C5.51293 3.27501 5.68626 3.18901 5.8636 3.11234L6.1356 1.85767C6.16798 1.70914 6.25015 1.57613 6.36849 1.48071C6.48684 1.38528 6.63424 1.33317 6.78626 1.33301H9.21293C9.36495 1.33317 9.51236 1.38528 9.6307 1.48071C9.74904 1.57613 9.83122 1.70914 9.8636 1.85767L10.1383 3.11301C10.4998 3.27252 10.8429 3.47079 11.1616 3.70434L12.3869 3.31434C12.5318 3.26829 12.6881 3.27312 12.8298 3.32805C12.9715 3.38298 13.0903 3.48469 13.1663 3.61634L14.3796 5.71834C14.5343 5.98967 14.4809 6.33301 14.2509 6.54434L13.3009 7.41101C13.3441 7.8036 13.3441 8.19975 13.3009 8.59234L14.2509 9.45901C14.4809 9.67101 14.5343 10.0137 14.3796 10.285L13.1663 12.387C13.0902 12.5188 12.9714 12.6206 12.8295 12.6755C12.6876 12.7305 12.5312 12.7352 12.3863 12.689L11.1616 12.299C10.8437 12.5332 10.5007 12.7313 10.1389 12.8897L9.8636 14.1423C9.83108 14.2908 9.74885 14.4236 9.63052 14.5189C9.51219 14.6142 9.36486 14.6662 9.21293 14.6663ZM7.99693 5.33301C7.28969 5.33301 6.61141 5.61396 6.11131 6.11406C5.61122 6.61415 5.33026 7.29243 5.33026 7.99967C5.33026 8.70692 5.61122 9.38519 6.11131 9.88529C6.61141 10.3854 7.28969 10.6663 7.99693 10.6663C8.70418 10.6663 9.38245 10.3854 9.88255 9.88529C10.3826 9.38519 10.6636 8.70692 10.6636 7.99967C10.6636 7.29243 10.3826 6.61415 9.88255 6.11406C9.38245 5.61396 8.70418 5.33301 7.99693 5.33301Z"
                            fill="#1E1E1E" />
                    </svg>
                } />
                <GatewayItem label={__('Offline Donation', 'give')} icon={
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M15.3337 5.33366V12.0003C15.3337 12.7337 14.7337 13.3337 14.0003 13.3337H3.33366C2.96699 13.3337 2.66699 13.0337 2.66699 12.667C2.66699 12.3003 2.96699 12.0003 3.33366 12.0003H14.0003V5.33366C14.0003 4.96699 14.3003 4.66699 14.667 4.66699C15.0337 4.66699 15.3337 4.96699 15.3337 5.33366ZM2.66699 10.667C1.56033 10.667 0.666992 9.77366 0.666992 8.66699V4.66699C0.666992 3.56033 1.56033 2.66699 2.66699 2.66699H10.667C11.7737 2.66699 12.667 3.56033 12.667 4.66699V9.33366C12.667 10.067 12.067 10.667 11.3337 10.667H2.66699ZM4.66699 6.66699C4.66699 7.77366 5.56033 8.66699 6.66699 8.66699C7.77366 8.66699 8.66699 7.77366 8.66699 6.66699C8.66699 5.56033 7.77366 4.66699 6.66699 4.66699C5.56033 4.66699 4.66699 5.56033 4.66699 6.66699Z"
                            fill="#1E1E1E" />
                    </svg>
                } />
                <GatewayItem label={__('Stripe - Credit Card', 'give')} icon={
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M12.6663 3.33301H3.33301C2.80257 3.33301 2.29387 3.54372 1.91879 3.91879C1.54372 4.29387 1.33301 4.80257 1.33301 5.33301V10.6663C1.33301 11.1968 1.54372 11.7055 1.91879 12.0806C2.29387 12.4556 2.80257 12.6663 3.33301 12.6663H12.6663C13.1968 12.6663 13.7055 12.4556 14.0806 12.0806C14.4556 11.7055 14.6663 11.1968 14.6663 10.6663V5.33301C14.6663 4.80257 14.4556 4.29387 14.0806 3.91879C13.7055 3.54372 13.1968 3.33301 12.6663 3.33301ZM7.33301 9.99967H4.66634C4.48953 9.99967 4.31996 9.92944 4.19494 9.80441C4.06991 9.67939 3.99967 9.50982 3.99967 9.33301C3.99967 9.1562 4.06991 8.98663 4.19494 8.8616C4.31996 8.73658 4.48953 8.66634 4.66634 8.66634H7.33301C7.50982 8.66634 7.67939 8.73658 7.80441 8.8616C7.92944 8.98663 7.99967 9.1562 7.99967 9.33301C7.99967 9.50982 7.92944 9.67939 7.80441 9.80441C7.67939 9.92944 7.50982 9.99967 7.33301 9.99967ZM11.333 9.99967H9.99967C9.82286 9.99967 9.65329 9.92944 9.52827 9.80441C9.40324 9.67939 9.33301 9.50982 9.33301 9.33301C9.33301 9.1562 9.40324 8.98663 9.52827 8.8616C9.65329 8.73658 9.82286 8.66634 9.99967 8.66634H11.333C11.5098 8.66634 11.6794 8.73658 11.8044 8.8616C11.9294 8.98663 11.9997 9.1562 11.9997 9.33301C11.9997 9.50982 11.9294 9.67939 11.8044 9.80441C11.6794 9.92944 11.5098 9.99967 11.333 9.99967ZM13.333 5.99967H2.66634V5.33301C2.66634 5.1562 2.73658 4.98663 2.8616 4.8616C2.98663 4.73658 3.1562 4.66634 3.33301 4.66634H12.6663C12.8432 4.66634 13.0127 4.73658 13.1377 4.8616C13.2628 4.98663 13.333 5.1562 13.333 5.33301V5.99967Z"
                            fill="#1E1E1E" />
                    </svg>
                } />
            </div>
        </div>,
        icon: () => <Icon icon={
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M11.3158 4L17.0774 5.824C17.2381 5.87483 17.3785 5.97639 17.4782 6.11383C17.5779 6.25128 17.6317 6.41742 17.6316 6.588V8H19.2105C19.4199 8 19.6207 8.08429 19.7688 8.23431C19.9168 8.38434 20 8.58783 20 8.8V10.4H9.73684V8.8C9.73684 8.58783 9.82002 8.38434 9.96807 8.23431C10.1161 8.08429 10.3169 8 10.5263 8H16.0526V7.176L11.3158 5.6752L6.57895 7.176V13.0992C6.57883 13.589 6.68967 14.0723 6.90292 14.5118C7.11617 14.9513 7.42614 15.3354 7.80895 15.6344L7.95816 15.7432L11.3158 18.064L14.3016 16H10.5263C10.3169 16 10.1161 15.9157 9.96807 15.7657C9.82002 15.6157 9.73684 15.4122 9.73684 15.2V12H20V15.2C20 15.4122 19.9168 15.6157 19.7688 15.7657C19.6207 15.9157 19.4199 16 19.2105 16L16.6684 16.0008C16.3629 16.4088 15.9918 16.7688 15.5632 17.0648L11.3158 20L7.06842 17.0656C6.43094 16.6252 5.90928 16.0336 5.54879 15.3423C5.18831 14.6511 4.99992 13.881 5 13.0992V6.588C5.0001 6.41755 5.05391 6.25159 5.1536 6.11431C5.25329 5.97702 5.39365 5.87559 5.55421 5.8248L11.3158 4Z"
                    fill="#000C00" />
            </svg>
        } />,
    },
};

const GatewayItem = ({label, icon}) => {
    return (
        <div style={{
            backgroundColor: 'var(--give-gray-20)', padding: '16px', display: 'flex', justifyContent: 'space-between',
        }}>
            {label} {icon}
        </div>
    );
};

export default paymentGateways;
