<x-app-layout>
    <x-home.banner></x-home.banner>
    <div class="flex flex-row mt-4 content-between">
        <x-slideshow :slides="$slides"></x-slideshow>
        <div class="flex flex-col items-center ml-5 w-2/6">
            <x-home.card href="https://us-east-1.console.aws.amazon.com/ec2/home?region=us-east-1#Instances:v=3;$case=tags:true%5C,client:false;$regex=tags:false%5C,client:false" target="_blank"
                image="{{ asset('images/aws.png') }}" alt="Logo aws ">

                <x-slot name="title">Maquinas AWS</x-slot>

                <ul class="text-xs pt-0">
                   
                    <li
                        class="absolute bottom-3 right-1 bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-green-200 dark:text-green-900">
                        Veja mais</li>
                </ul>
            </x-home.card>
        </div>
    </div>
</x-app-layout>
