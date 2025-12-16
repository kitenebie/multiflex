<!doctype html>
<html lang="en" class="h-full">
 <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Payslip</title>
  <script src="/_sdk/element_sdk.js"></script>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      box-sizing: border-box;
    }
    
    @media print {
      .no-print {
        display: none;
      }
      body {
        background: white !important;
      }
    }
  </style>
  <style>@view-transition { navigation: auto; }</style>
  <script src="/_sdk/data_sdk.js" type="text/javascript"></script>
 </head>
 <body class="h-full">
  <div class="w-full h-full overflow-auto bg-gradient-to-br from-slate-100 to-slate-200 p-8">
   <div class="max-w-4xl mx-auto bg-white shadow-2xl rounded-lg overflow-hidden"><!-- Header -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white p-8">
     <div class="flex items-center justify-between">
      <div>
       <h1 id="company-name" class="text-3xl font-bold mb-2">GMS Fitness Center</h1>
       <p id="company-address" class="text-indigo-100">Manila, Philippines</p>
      </div>
      <div class="text-right">
       <div class="text-4xl mb-2">
        💼
       </div>
       <h2 class="text-xl font-semibold">PAYSLIP</h2>
      </div>
     </div>
    </div><!-- Employee Info -->
    <div class="p-8 border-b-2 border-indigo-100">
     <div class="grid grid-cols-2 gap-6">
      <div><label class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Employee Name</label>
       <p id="employee-name" class="text-lg font-medium text-gray-900 mt-1">John Smith</p>
      </div>
      <div><label class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Employee ID</label>
       <p id="employee-id" class="text-lg font-medium text-gray-900 mt-1">EMP-2024-001</p>
      </div>
      <div><label class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Designation</label>
       <p id="designation" class="text-lg font-medium text-gray-900 mt-1">Senior Software Engineer</p>
      </div>
      <div><label class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Department</label>
       <p id="department" class="text-lg font-medium text-gray-900 mt-1">Engineering</p>
      </div>
      <div><label class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Pay Period</label>
       <p id="pay-period" class="text-lg font-medium text-gray-900 mt-1">January 2024</p>
      </div>
      <div><label class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Payment Date</label>
       <p id="pay-date" class="text-lg font-medium text-gray-900 mt-1">31 Jan 2024</p>
      </div>
     </div>
    </div><!-- Earnings and Deductions -->
    <div class="p-8">
     <div class="grid grid-cols-2 gap-8"><!-- Earnings -->
      <div>
       <h3 class="text-xl font-bold text-gray-900 mb-4 pb-2 border-b-2 border-green-500">Earnings</h3>
       <div class="space-y-3">
        <div class="flex justify-between"><span class="text-gray-700">Basic Salary</span> <span class="font-semibold text-gray-900">$5,000.00</span>
        </div>
        <div class="flex justify-between"><span class="text-gray-700">House Rent Allowance</span> <span class="font-semibold text-gray-900">$1,500.00</span>
        </div>
        <div class="flex justify-between"><span class="text-gray-700">Transport Allowance</span> <span class="font-semibold text-gray-900">$500.00</span>
        </div>
        <div class="flex justify-between"><span class="text-gray-700">Performance Bonus</span> <span class="font-semibold text-gray-900">$1,000.00</span>
        </div>
        <div class="flex justify-between pt-3 border-t-2 border-gray-200"><span class="font-bold text-gray-900">Total Earnings</span> <span class="font-bold text-green-600 text-lg">$8,000.00</span>
        </div>
       </div>
      </div><!-- Deductions -->
      <div>
       <h3 class="text-xl font-bold text-gray-900 mb-4 pb-2 border-b-2 border-red-500">Deductions</h3>
       <div class="space-y-3">
        <div class="flex justify-between"><span class="text-gray-700">Income Tax</span> <span class="font-semibold text-gray-900">$800.00</span>
        </div>
        <div class="flex justify-between"><span class="text-gray-700">Social Security</span> <span class="font-semibold text-gray-900">$400.00</span>
        </div>
        <div class="flex justify-between"><span class="text-gray-700">Health Insurance</span> <span class="font-semibold text-gray-900">$200.00</span>
        </div>
        <div class="flex justify-between"><span class="text-gray-700">Provident Fund</span> <span class="font-semibold text-gray-900">$600.00</span>
        </div>
        <div class="flex justify-between pt-3 border-t-2 border-gray-200"><span class="font-bold text-gray-900">Total Deductions</span> <span class="font-bold text-red-600 text-lg">$2,000.00</span>
        </div>
       </div>
      </div>
     </div>
    </div><!-- Net Pay -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white p-8">
     <div class="flex justify-between items-center">
      <div>
       <p class="text-indigo-100 text-sm uppercase tracking-wide mb-1">Net Pay Amount</p>
       <p class="text-4xl font-bold">$6,000.00</p>
      </div>
      <div class="text-right">
       <p class="text-indigo-100 text-sm">Amount in Words</p>
       <p class="text-lg font-semibold">Six Thousand Dollars Only</p>
      </div>
     </div>
    </div><!-- Footer -->
    <div class="bg-gray-50 p-6 text-center border-t">
     <p class="text-sm text-gray-600">This is a computer-generated payslip and does not require a signature.</p>
     <p class="text-xs text-gray-500 mt-2">For queries, please contact HR Department</p>
    </div><!-- Print Button -->
    <div class="p-6 no-print text-center"><button onclick="window.print()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-8 py-3 rounded-lg shadow-lg transition-all duration-200 transform hover:scale-105"> 🖨️ Print Payslip </button>
    </div>
   </div>
  </div>
  <script>
    const defaultConfig = {
      company_name: "Acme Corporation",
      company_address: "123 Business Street, New York, NY 10001",
      employee_name: "John Smith",
      employee_id: "EMP-2024-001",
      designation: "Senior Software Engineer",
      department: "Engineering",
      pay_period: "January 2024",
      pay_date: "31 Jan 2024",
      background_color: "#f1f5f9",
      surface_color: "#ffffff",
      text_color: "#111827",
      primary_action_color: "#4f46e5",
      secondary_action_color: "#7c3aed",
      font_family: "system-ui",
      font_size: 16
    };

    async function onConfigChange(config) {
      const customFont = config.font_family || defaultConfig.font_family;
      const baseFontStack = '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif';
      const fontFamily = `${customFont}, ${baseFontStack}`;
      const baseSize = config.font_size || defaultConfig.font_size;
      
      document.body.style.fontFamily = fontFamily;
      
      document.getElementById('company-name').textContent = config.company_name || defaultConfig.company_name;
      document.getElementById('company-name').style.fontSize = `${baseSize * 1.875}px`;
      
      document.getElementById('company-address').textContent = config.company_address || defaultConfig.company_address;
      document.getElementById('company-address').style.fontSize = `${baseSize}px`;
      
      document.getElementById('employee-name').textContent = config.employee_name || defaultConfig.employee_name;
      document.getElementById('employee-name').style.fontSize = `${baseSize * 1.125}px`;
      
      document.getElementById('employee-id').textContent = config.employee_id || defaultConfig.employee_id;
      document.getElementById('employee-id').style.fontSize = `${baseSize * 1.125}px`;
      
      document.getElementById('designation').textContent = config.designation || defaultConfig.designation;
      document.getElementById('designation').style.fontSize = `${baseSize * 1.125}px`;
      
      document.getElementById('department').textContent = config.department || defaultConfig.department;
      document.getElementById('department').style.fontSize = `${baseSize * 1.125}px`;
      
      document.getElementById('pay-period').textContent = config.pay_period || defaultConfig.pay_period;
      document.getElementById('pay-period').style.fontSize = `${baseSize * 1.125}px`;
      
      document.getElementById('pay-date').textContent = config.pay_date || defaultConfig.pay_date;
      document.getElementById('pay-date').style.fontSize = `${baseSize * 1.125}px`;
      
      const backgroundColor = config.background_color || defaultConfig.background_color;
      const surfaceColor = config.surface_color || defaultConfig.surface_color;
      const textColor = config.text_color || defaultConfig.text_color;
      const primaryActionColor = config.primary_action_color || defaultConfig.primary_action_color;
      const secondaryActionColor = config.secondary_action_color || defaultConfig.secondary_action_color;
      
      document.querySelector('.bg-gradient-to-br').style.background = `linear-gradient(to bottom right, ${backgroundColor}, ${backgroundColor})`;
      document.querySelector('.max-w-4xl').style.backgroundColor = surfaceColor;
      document.querySelectorAll('.text-gray-900').forEach(el => el.style.color = textColor);
      document.querySelectorAll('.bg-gradient-to-r').forEach(el => {
        el.style.background = `linear-gradient(to right, ${primaryActionColor}, ${secondaryActionColor})`;
      });
      document.querySelector('button').style.backgroundColor = primaryActionColor;
    }

    function mapToCapabilities(config) {
      return {
        recolorables: [
          {
            get: () => config.background_color || defaultConfig.background_color,
            set: (value) => {
              if (window.elementSdk) {
                window.elementSdk.config.background_color = value;
                window.elementSdk.setConfig({ background_color: value });
              }
            }
          },
          {
            get: () => config.surface_color || defaultConfig.surface_color,
            set: (value) => {
              if (window.elementSdk) {
                window.elementSdk.config.surface_color = value;
                window.elementSdk.setConfig({ surface_color: value });
              }
            }
          },
          {
            get: () => config.text_color || defaultConfig.text_color,
            set: (value) => {
              if (window.elementSdk) {
                window.elementSdk.config.text_color = value;
                window.elementSdk.setConfig({ text_color: value });
              }
            }
          },
          {
            get: () => config.primary_action_color || defaultConfig.primary_action_color,
            set: (value) => {
              if (window.elementSdk) {
                window.elementSdk.config.primary_action_color = value;
                window.elementSdk.setConfig({ primary_action_color: value });
              }
            }
          },
          {
            get: () => config.secondary_action_color || defaultConfig.secondary_action_color,
            set: (value) => {
              if (window.elementSdk) {
                window.elementSdk.config.secondary_action_color = value;
                window.elementSdk.setConfig({ secondary_action_color: value });
              }
            }
          }
        ],
        borderables: [],
        fontEditable: {
          get: () => config.font_family || defaultConfig.font_family,
          set: (value) => {
            if (window.elementSdk) {
              window.elementSdk.config.font_family = value;
              window.elementSdk.setConfig({ font_family: value });
            }
          }
        },
        fontSizeable: {
          get: () => config.font_size || defaultConfig.font_size,
          set: (value) => {
            if (window.elementSdk) {
              window.elementSdk.config.font_size = value;
              window.elementSdk.setConfig({ font_size: value });
            }
          }
        }
      };
    }

    function mapToEditPanelValues(config) {
      return new Map([
        ["company_name", config.company_name || defaultConfig.company_name],
        ["company_address", config.company_address || defaultConfig.company_address],
        ["employee_name", config.employee_name || defaultConfig.employee_name],
        ["employee_id", config.employee_id || defaultConfig.employee_id],
        ["designation", config.designation || defaultConfig.designation],
        ["department", config.department || defaultConfig.department],
        ["pay_period", config.pay_period || defaultConfig.pay_period],
        ["pay_date", config.pay_date || defaultConfig.pay_date]
      ]);
    }

    if (window.elementSdk) {
      window.elementSdk.init({
        defaultConfig,
        onConfigChange,
        mapToCapabilities,
        mapToEditPanelValues
      });
    }
  </script>
 <script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="window.__CF$cv$params={r:'9aede1136345bc43',t:'MTc2NTg4NDk0Ni4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute';a.style.top=0;a.style.left=0;a.style.border='none';a.style.visibility='hidden';document.body.appendChild(a);if('loading'!==document.readyState)c();else if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script></body>
</html>