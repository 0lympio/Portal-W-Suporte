@props(['link', 'image'])
<a class="flex pt-0 pr-0 xl:pt-5 py-4 lg:h-32 xl:h-40 w-full" href="https://us-east-1.console.aws.amazon.com/ec2/home?region=us-east-1#Instances:v=3;$case=tags:true%5C,client:false;$regex=tags:false%5C,client:false" target="_blank">
        <img class="xl:w-1/3 max-w-[60%] hover:scale-100 transition-all duration-150 ease-out hover:ease-in shadow object-fit z-50"
            src="{{ $image }}" alt="Imagem AWS" />

    <div class="flex flex-col justify-start w-full h-32 bg-white relative">
        <span class="ml-2 mt-2 text-gray-900 xl:text-xl text-md font-medium lg:text-sm">{{ $title }}</span>
        {{ $slot }}
    </div>
</a>
